<?php

namespace App\Filament\Merchant\Pages;

use App\Models\Staff;
use App\Models\OperatingHour;
use App\Models\StaffOperatingHour;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class StaffOperatingHours extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'Staff Operating Hours';
    protected static ?string $navigationIcon  = 'heroicon-o-clock';
    // Hide from sidebar navigation
    protected static bool $shouldRegisterNavigation = true;
    protected static ?string $navigationGroup = 'Account';
    protected static ?int    $navigationSort  = 21;

    protected static ?string $title = 'Staff Operating Hours';
    protected static ?string $slug  = 'staff-operating-hours';
    protected static string $view = 'filament.merchant.pages.staff-operating-hours';

    /** Staff record being edited (scoped to current merchant) */
    public ?Staff $staff = null;

    /** Selected staff id from dropdown */
    public ?int $selectedStaffId = null;

    /** Toggle to enable/disable editing mode (same pattern as MyProfile) */
    public bool $isEditing = false;

    /** Form state holder */
    public ?array $data = [];

    /** Load latest blocks from DB and fill the form, preserving existing form state (such as selectedStaffId) */
    private function fillBlocksFromDb(): void
    {
        // If no staff chosen yet, keep existing state and clear blocks only.
        if (! $this->staff?->exists) {
            $state = $this->form->getState() ?? [];
            $state['blocks'] = [];
            $this->form->fill($state);
            return;
        }

        $blocks = $this->staff->operatingHours()
            ->orderBy('day_of_week')
            ->orderBy('block_index')
            ->get()
            ->map(fn (StaffOperatingHour $oh) => [
                'day_of_week' => (int) $oh->day_of_week,
                'block_type'  => $oh->block_type,            // 'open' | 'break' | 'closed'
                'label'       => $oh->label,
                'start_time'  => $oh->start_time ? substr($oh->start_time, 0, 5) : null,
                'end_time'    => $oh->end_time ? substr($oh->end_time, 0, 5) : null,
            ])->all();

        // Preserve other form fields (like selectedStaffId) while updating blocks
        $state = $this->form->getState() ?? [];
        $state['selectedStaffId'] = $this->selectedStaffId;
        $state['blocks'] = $blocks;

        $this->form->fill($state);
    }

    public function mount(): void
    {
        $staffId = (int) request()->query('staff_id', 0);
        $merchantId = auth()->user()?->merchantProfile?->id;

        if ($staffId) {
            $this->staff = Staff::query()
                ->where('id', $staffId)
                ->where('merchant_id', $merchantId)
                ->firstOrFail();
            $this->selectedStaffId = $staffId;
            $this->fillBlocksFromDb();
        }

        $this->isEditing = false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Select::make('selectedStaffId')
                    ->label('Choose Staff')
                    ->options(fn () => Staff::where('merchant_id', auth()->user()?->merchantProfile?->id)
                        ->pluck('name','id'))
                    ->reactive()
                    ->afterStateHydrated(function ($state, callable $set) {
                        if ($this->selectedStaffId && $state !== $this->selectedStaffId) {
                            $set('selectedStaffId', $this->selectedStaffId);
                        }
                    })
                    ->afterStateUpdated(function ($state) {
                        if ($state) {
                            $this->selectedStaffId = (int) $state;
                            $this->staff = Staff::find($this->selectedStaffId);
                            $this->fillBlocksFromDb();
                        }
                    })
                    ->required(),
                Section::make('Operating Hours (Open / Break Blocks)')
                    ->columns(1)
                    ->headerActions([
                        FormAction::make('editHours')
                            ->label('Edit')
                            ->icon('heroicon-o-pencil-square')
                            ->action('startEdit')
                            ->visible(fn (): bool => ! $this->isEditing),
                        FormAction::make('saveHours')
                            ->label('Save changes')
                            ->icon('heroicon-o-check')
                            ->color('success')
                            ->requiresConfirmation()
                            ->action('save')
                            ->visible(fn (): bool => $this->isEditing),
                        FormAction::make('copyHours')
                            ->label('Copy hours')
                            ->icon('heroicon-o-clipboard-document')
                            ->visible(fn (): bool => $this->isEditing)
                            ->form([
                                Select::make('source_day')
                                    ->label('Copy from')
                                    ->options([
                                        0 => 'Sunday', 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday',
                                        4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday',
                                    ])
                                    ->required(),
                                Select::make('target_days')
                                    ->label('Copy to')
                                    ->options([
                                        0 => 'Sunday', 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday',
                                        4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday',
                                    ])
                                    ->required()
                                    ->multiple(),
                                Select::make('mode')
                                    ->label('Mode')
                                    ->options([
                                        'replace' => 'Replace target day(s)',
                                        'append'  => 'Append to target day(s)',
                                    ])
                                    ->default('replace')
                                    ->required(),
                            ])
                            ->action(function (array $data): void {
                                $state = $this->form->getState();
                                $blocks = $state['blocks'] ?? [];

                                $src = (int)($data['source_day'] ?? -1);
                                $targets = array_map('intval', $data['target_days'] ?? []);
                                $mode = $data['mode'] ?? 'replace';

                                if ($src < 0 || empty($targets)) {
                                    return;
                                }

                                // Extract source rows
                                $sourceRows = array_values(array_filter($blocks, fn ($b) => (int)$b['day_of_week'] === $src));

                                // If nothing to copy, just return
                                if (count($sourceRows) === 0) {
                                    return;
                                }

                                // If the source contains a 'closed' row, use only a single closed row as canonical
                                $hasClosed = false;
                                foreach ($sourceRows as $r) {
                                    if (($r['block_type'] ?? null) === 'closed') {
                                        $hasClosed = true;
                                        break;
                                    }
                                }
                                if ($hasClosed) {
                                    $sourceRows = [[
                                        'day_of_week' => $src,
                                        'block_type'  => 'closed',
                                        'label'       => null,
                                        'start_time'  => null,
                                        'end_time'    => null,
                                    ]];
                                }

                                // Prepare a new blocks array based on mode
                                $newBlocks = $blocks;

                                foreach ($targets as $t) {
                                    // Remove existing rows for target if mode = replace
                                    if ($mode === 'replace') {
                                        $newBlocks = array_values(array_filter($newBlocks, fn ($b) => (int)$b['day_of_week'] !== $t));
                                    }

                                    // Append mapped copies
                                    foreach ($sourceRows as $row) {
                                        $copy = $row;
                                        $copy['day_of_week'] = $t;
                                        $newBlocks[] = $copy;
                                    }
                                }

                                // Fill back to the form
                                $this->form->fill(['blocks' => array_values($newBlocks)]);
                            }),
                        FormAction::make('importFromMerchant')
                            ->label('Copy from Shop Hours')
                            ->icon('heroicon-o-arrow-down-tray')
                            ->visible(fn (): bool => $this->isEditing && $this->staff?->exists)
                            ->requiresConfirmation()
                            ->modalHeading('Copy shop (merchant) hours into this staff schedule?')
                            ->modalDescription('This will replace the current blocks with the merchant\'s Operating Hours (Open/Break/Closed). You can still edit before saving.')
                            ->action(function (): void {
                                // Guard
                                if (! $this->staff?->exists) {
                                    return;
                                }

                                // Fetch merchant hours for this staff's merchant
                                $merchantHours = OperatingHour::query()
                                    ->where('merchant_profile_id', $this->staff->merchant_id)
                                    ->orderBy('day_of_week')
                                    ->orderBy('block_index')
                                    ->get()
                                    ->groupBy('day_of_week');

                                $mapped = [];

                                foreach ($merchantHours as $day => $rows) {
                                    $day = (int) $day;

                                    // If merchant day has a CLOSED block, force a single closed row
                                    $hasClosed = $rows->contains(fn ($r) => ($r->block_type ?? null) === 'closed');
                                    if ($hasClosed) {
                                        $mapped[] = [
                                            'day_of_week' => $day,
                                            'block_type'  => 'closed',
                                            'label'       => null,
                                            'start_time'  => null,
                                            'end_time'    => null,
                                        ];
                                        continue;
                                    }

                                    // Otherwise map open/break rows directly
                                    foreach ($rows as $r) {
                                        $mapped[] = [
                                            'day_of_week' => $day,
                                            'block_type'  => $r->block_type ?? 'open',
                                            'label'       => $r->label,
                                            'start_time'  => $r->start_time ? substr($r->start_time, 0, 5) : null,
                                            'end_time'    => $r->end_time ? substr($r->end_time, 0, 5) : null,
                                        ];
                                    }
                                }

                                // Replace current blocks in the form with mapped merchant hours
                                $state = $this->form->getState() ?? [];
                                $state['blocks'] = $mapped; // full replace; user can still adjust before Save
                                $this->form->fill($state);

                                Notification::make()
                                    ->title('Copied from shop hours')
                                    ->success()
                                    ->body('Merchant operating hours loaded into the form. Review and click "Save changes" to persist.')
                                    ->send();
                            }),
                        FormAction::make('cancelHours')
                            ->label('Cancel')
                            ->icon('heroicon-o-x-mark')
                            ->color('gray')
                            ->action('cancelEdit')
                            ->visible(fn (): bool => $this->isEditing),
                    ])
                    ->schema([
                        Repeater::make('blocks')
                            ->label('Weekly Blocks')
                            ->helperText('Add Open / Break / Closed blocks. If a day is set to Closed, other blocks for that day are ignored. Overlaps are OK — on Save, we auto-split open blocks around breaks and sort them by time per day.')
                            ->columns(12)
                            ->addAction(fn ($action) => $action->disabled(fn () => ! $this->isEditing))
                            ->deleteAction(fn ($action) => $action->disabled(fn () => ! $this->isEditing))
                            ->reorderable(fn () => $this->isEditing)
                            ->schema([
                                Select::make('day_of_week')
                                    ->label('Day')
                                    ->options([
                                        0 => 'Sunday', 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday',
                                        4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday',
                                    ])
                                    ->required()
                                    ->disabled(fn () => ! $this->isEditing)
                                    ->columnSpan(3),

                                Select::make('block_type')
                                    ->label('Type')
                                    ->options([
                                        'open'   => 'Open',
                                        'break'  => 'Break',
                                        'closed' => 'Closed',
                                    ])
                                    ->required()
                                    ->disabled(fn () => ! $this->isEditing)
                                    ->columnSpan(2),

                                TextInput::make('label')
                                    ->label('Label (optional)')
                                    ->placeholder('Lunch / Cleaning / Prayer')
                                    ->maxLength(50)
                                    ->disabled(fn () => ! $this->isEditing)
                                    ->columnSpan(3),

                                TimePicker::make('start_time')
                                    ->label('Start')
                                    ->seconds(false)
                                    ->required(fn (Get $get) => $get('block_type') !== 'closed')
                                    ->disabled(fn (Get $get) => ! $this->isEditing || $get('block_type') === 'closed')
                                    ->columnSpan(2),

                                TimePicker::make('end_time')
                                    ->label('End')
                                    ->seconds(false)
                                    ->required(fn (Get $get) => $get('block_type') !== 'closed')
                                    ->disabled(fn (Get $get) => ! $this->isEditing || $get('block_type') === 'closed')
                                    ->after('start_time')
                                    ->columnSpan(2),
                            ])
                            ->default([]),
                    ]),
            ]);
    }

    /** Enable edit mode */
    public function startEdit(): void
    {
        $this->isEditing = true;
    }

    /** Cancel edit mode and reset to DB values */
    public function cancelEdit(): void
    {
        $this->isEditing = false;

        $this->fillBlocksFromDb();
    }

    /** Validate and persist changes (ORM/PDO) */
    public function save(): void
    {
        if (! $this->staff?->exists) {
            Notification::make()->title('No staff selected')->danger()->body('Please select a staff first.')->send();
            return;
        }
        // Authorization: ensure the staff belongs to the merchant
        if ($this->staff->merchant_id !== auth()->user()?->merchantProfile?->id) {
            Notification::make()->title('Unauthorized')->danger()->body('You cannot edit these hours.')->send();
            return;
        }

        $state = $this->form->getState();
        $blocks = Arr::get($state, 'blocks', []);

        // Basic validation: required fields, allow closed without times
        foreach ($blocks as $i => $b) {
            if (! isset($b['day_of_week'], $b['block_type'])) {
                Notification::make()->title('Invalid row')->danger()->body('Each block needs a day and type.')->send();
                return;
            }

            if (($b['block_type'] ?? null) === 'closed') {
                // closed rows: ignore any provided times/labels; no further checks
                continue;
            }

            if (! isset($b['start_time'], $b['end_time'])) {
                Notification::make()->title('Missing time')
                    ->danger()->body('Start and End are required for Open/Break.')->send();
                return;
            }

            if ($b['start_time'] >= $b['end_time']) {
                Notification::make()->title('Time error')->danger()->body('End time must be after start time.')->send();
                return;
            }
        }

        // --------- Validate against Merchant Operating Hours (staff must be subset) ----------
        // Load merchant blocks and normalize into OPEN windows per day
        $merchantBlocks = OperatingHour::query()
            ->where('merchant_profile_id', $this->staff->merchant_id)
            ->get()
            ->groupBy('day_of_week');

        // Helpers to work with HH:MM values as minutes
        $toM = function (string $hm): int {
            [$h, $m] = array_map('intval', explode(':', $hm));
            return $h * 60 + $m;
        };
        $toHM = function (int $mins): string {
            $h = intdiv($mins, 60); $m = $mins % 60;
            return str_pad((string)$h, 2, '0', STR_PAD_LEFT) . ':' . str_pad((string)$m, 2, '0', STR_PAD_LEFT);
        };
        $merge = function (array $items) use ($toM, $toHM): array {
            if (empty($items)) return [];
            usort($items, fn($a,$b) => $toM($a['start']) <=> $toM($b['start']));
            $merged = [];
            foreach ($items as $it) {
                if (empty($merged)) { $merged[] = $it; continue; }
                $last = &$merged[count($merged)-1];
                $lastStart = $toM($last['start']); $lastEnd = $toM($last['end']);
                $curStart  = $toM($it['start']);   $curEnd  = $toM($it['end']);
                if ($curStart <= $lastEnd) {
                    if ($curEnd > $lastEnd) { $last['end'] = $toHM($curEnd); }
                } else {
                    $merged[] = $it;
                }
                unset($last);
            }
            return $merged;
        };

        // Build merchant OPEN windows per day
        $merchantOpen = [];
        foreach ($merchantBlocks as $day => $items) {
            $day = (int) $day;
            // If merchant set CLOSED for the day → mark closed
            $hasClosed = $items->contains(fn ($r) => ($r->block_type ?? null) === 'closed');
            if ($hasClosed) {
                $merchantOpen[$day] = ['closed' => true];
                continue;
            }
            // Split into opens/breaks then normalize and subtract
            $opens = []; $breaks = [];
            foreach ($items as $r) {
                if ($r->block_type === 'break') {
                    $breaks[] = ['start' => $r->start_time, 'end' => $r->end_time];
                } elseif ($r->block_type === 'open') {
                    $opens[]  = ['start' => $r->start_time, 'end' => $r->end_time];
                }
            }
            $opens  = $merge($opens);
            $breaks = $merge($breaks);

            // Subtract breaks from opens → final merchant open windows
            $final = [];
            foreach ($opens as $op) {
                $segStart = $toM($op['start']); $segEnd = $toM($op['end']);
                foreach ($breaks as $br) {
                    $brStart = $toM($br['start']); $brEnd = $toM($br['end']);
                    if ($brEnd <= $segStart || $brStart >= $segEnd) { continue; }
                    if ($brStart > $segStart) {
                        $final[] = ['start' => $toHM($segStart), 'end' => $toHM($brStart)];
                    }
                    $segStart = max($segStart, $brEnd);
                    if ($segStart >= $segEnd) { break; }
                }
                if ($segStart < $segEnd) {
                    $final[] = ['start' => $toHM($segStart), 'end' => $toHM($segEnd)];
                }
            }
            $merchantOpen[$day] = $final; // array of windows or ['closed'=>true]
        }

        // Validate staff blocks day-by-day against merchant windows
        $dayNames = [0=>'Sunday',1=>'Monday',2=>'Tuesday',3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday'];
        $byDay = collect($blocks)->groupBy('day_of_week');

        foreach ($byDay as $day => $items) {
            $day = (int) $day;
            $merchantWindows = $merchantOpen[$day] ?? [];

            // Merchant closed day
            if (isset($merchantWindows['closed']) && $merchantWindows['closed'] === true) {
                // Any non-closed staff block is invalid
                $hasNonClosed = collect($items)->contains(fn ($r) => ($r['block_type'] ?? null) !== 'closed');
                if ($hasNonClosed) {
                    Notification::make()
                        ->title('Merchant closed on ' . ($dayNames[$day] ?? 'this day'))
                        ->danger()
                        ->body('Staff hours cannot be set on a day the shop is closed.')
                        ->send();
                    return;
                }
                continue;
            }

            // Otherwise, ensure each staff OPEN block is entirely within any merchant open window
            foreach ($items as $r) {
                if (($r['block_type'] ?? null) !== 'open') {
                    continue; // breaks/closed are okay relative to merchant (they don't extend availability)
                }
                $s = $toM($r['start_time']); $e = $toM($r['end_time']);
                $inside = false;
                foreach ($merchantWindows as $w) {
                    if ($s >= $toM($w['start']) && $e <= $toM($w['end'])) {
                        $inside = true; break;
                    }
                }
                if (! $inside) {
                    Notification::make()
                        ->title('Outside merchant hours on ' . ($dayNames[$day] ?? 'this day'))
                        ->danger()
                        ->body('Open block ' . ($r['start_time'] ?? '?') . '–' . ($r['end_time'] ?? '?') . ' is outside merchant operating hours.')
                        ->send();
                    return;
                }
            }
        }

        DB::transaction(function () use ($blocks) {
            // Helpers to work with HH:MM values as minutes
            $toM = function (string $hm): int {
                [$h, $m] = array_map('intval', explode(':', $hm));
                return $h * 60 + $m;
            };
            $toHM = function (int $mins): string {
                $h = intdiv($mins, 60); $m = $mins % 60;
                return str_pad((string)$h, 2, '0', STR_PAD_LEFT) . ':' . str_pad((string)$m, 2, '0', STR_PAD_LEFT);
            };

            // Merge intervals (overlap or touch) within an array of [start,end,label]
            $merge = function (array $items) use ($toM, $toHM): array {
                if (empty($items)) return [];
                usort($items, fn($a,$b) => $toM($a['start']) <=> $toM($b['start']));
                $merged = [];
                foreach ($items as $it) {
                    if (empty($merged)) { $merged[] = $it; continue; }
                    $last = &$merged[count($merged)-1];
                    $lastStart = $toM($last['start']);
                    $lastEnd   = $toM($last['end']);
                    $curStart  = $toM($it['start']);
                    $curEnd    = $toM($it['end']);
                    // overlap or touch
                    if ($curStart <= $lastEnd) {
                        if ($curEnd > $lastEnd) { $last['end'] = $toHM($curEnd); }
                        // keep earliest label (prefer existing)
                    } else {
                        $merged[] = $it;
                    }
                    unset($last); // break the reference
                }
                return $merged;
            };

            // Wipe existing rows first
            $this->staff->operatingHours()->delete();

            // Group input by day and normalize
            $grouped = collect($blocks)->groupBy('day_of_week');
            foreach ($grouped as $day => $items) {
                // If the day has any 'closed' entry, persist a single closed row and skip others
                $hasClosed = collect($items)->contains(fn ($r) => ($r['block_type'] ?? null) === 'closed');
                if ($hasClosed) {
                    $this->staff->operatingHours()->create([
                        'day_of_week' => (int) $day,
                        'block_index' => 1,
                        'start_time'  => null,
                        'end_time'    => null,
                        'block_type'  => 'closed',
                        'label'       => null,
                    ]);
                    continue; // ignore any other blocks for this day
                }

                $day = (int) $day;
                $opens = [];
                $breaks = [];
                foreach ($items as $b) {
                    $row = [
                        'start' => $b['start_time'],
                        'end'   => $b['end_time'],
                        'label' => $b['label'] ?? null,
                    ];
                    if ($b['block_type'] === 'break') { $breaks[] = $row; } else { $opens[] = $row; }
                }

                // Merge overlapping/touching intervals within each category
                $opens  = $merge($opens);
                $breaks = $merge($breaks);

                // Subtract breaks from opens → produce final open segments
                $finalOpens = [];
                foreach ($opens as $op) {
                    $segStart = $toM($op['start']);
                    $segEnd   = $toM($op['end']);
                    foreach ($breaks as $br) {
                        $brStart = $toM($br['start']);
                        $brEnd   = $toM($br['end']);
                        // no overlap
                        if ($brEnd <= $segStart || $brStart >= $segEnd) {
                            continue;
                        }
                        // overlap: emit piece before break, then move start forward
                        if ($brStart > $segStart) {
                            $finalOpens[] = [
                                'start' => $toHM($segStart),
                                'end'   => $toHM($brStart),
                                'label' => $op['label'] ?? null,
                            ];
                        }
                        $segStart = max($segStart, $brEnd);
                        if ($segStart >= $segEnd) { break; }
                    }
                    // leftover tail after last break
                    if ($segStart < $segEnd) {
                        $finalOpens[] = [
                            'start' => $toHM($segStart),
                            'end'   => $toHM($segEnd),
                            'label' => $op['label'] ?? null,
                        ];
                    }
                }

                // Compose final blocks = (normalized breaks) + (split opens), sorted by start time
                $final = [];
                foreach ($breaks as $br) {
                    $final[] = [
                        'type'  => 'break',
                        'start' => $br['start'],
                        'end'   => $br['end'],
                        'label' => $br['label'] ?? null,
                    ];
                }
                foreach ($finalOpens as $op) {
                    $final[] = [
                        'type'  => 'open',
                        'start' => $op['start'],
                        'end'   => $op['end'],
                        'label' => $op['label'] ?? null,
                    ];
                }

                usort($final, fn($a,$b) => $toM($a['start']) <=> $toM($b['start']));

                // Persist with sequential block_index
                $idx = 1;
                foreach ($final as $row) {
                    $this->staff->operatingHours()->create([
                        'day_of_week' => $day,
                        'block_index' => $idx++,
                        'start_time'  => $row['start'] ?? null,
                        'end_time'    => $row['end'] ?? null,
                        'block_type'  => $row['type'], // 'open' or 'break'; 'closed' handled earlier
                        'label'       => $row['label'] ?? null,
                    ]);
                }
            }
        });

        $this->isEditing = false;

        $this->fillBlocksFromDb();
        $this->dispatch('saved'); // Filament event for forms

        Notification::make()
            ->title('Operating hours updated')
            ->success()
            ->body('Your weekly open and break blocks have been saved.')
            ->send();
    }

    protected function getViewData(): array
    {
        return [
            'staff' => $this->staff,
        ];
    }
}