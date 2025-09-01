<?php

namespace App\Filament\Merchant\Pages;

use App\Models\MerchantProfile;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class OperatingHours extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'Operating Hours';

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'Account';

    protected static ?int $navigationSort = 21;

    protected static ?string $title = 'Operating Hours';

    protected static ?string $slug = 'operating-hours';

    protected static string $view = 'filament.merchant.pages.operating-hours';

    /** Merchant profile loaded for the logged-in user */
    public MerchantProfile $profile;

    /** Toggle to enable/disable editing mode (same pattern as MyProfile) */
    public bool $isEditing = false;

    /** Form state holder */
    public ?array $data = [];

    /** Load latest blocks from DB and fill the form */
    private function fillBlocksFromDb(): void
    {
        $blocks = $this->profile->operatingHours()
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->map(fn ($oh) => [
                'day_of_week' => (int) $oh->day_of_week,
                'block_type' => $oh->block_type,
                'label' => $oh->label,
                'start_time' => $oh->start_time ? substr($oh->start_time, 0, 5) : null,
                'end_time' => $oh->end_time ? substr($oh->end_time, 0, 5) : null,
            ])->all();

        $this->form->fill(['blocks' => $blocks]);
    }

    public function mount(): void
    {
        $this->profile = MerchantProfile::query()
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Pre-fill form from DB
        $this->fillBlocksFromDb();

        $this->isEditing = false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
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
                                        'append' => 'Append to target day(s)',
                                    ])
                                    ->default('replace')
                                    ->required(),
                            ])
                            ->action(function (array $data): void {
                                $state = $this->form->getState();
                                $blocks = $state['blocks'] ?? [];

                                $src = (int) ($data['source_day'] ?? -1);
                                $targets = array_map('intval', $data['target_days'] ?? []);
                                $mode = $data['mode'] ?? 'replace';

                                if ($src < 0 || empty($targets)) {
                                    return;
                                }

                                // Extract source rows
                                $sourceRows = array_values(array_filter($blocks, fn ($b) => (int) $b['day_of_week'] === $src));

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
                                        'block_type' => 'closed',
                                        'label' => null,
                                        'start_time' => null,
                                        'end_time' => null,
                                    ]];
                                }

                                // Prepare a new blocks array based on mode
                                $newBlocks = $blocks;

                                foreach ($targets as $t) {
                                    // Remove existing rows for target if mode = replace
                                    if ($mode === 'replace') {
                                        $newBlocks = array_values(array_filter($newBlocks, fn ($b) => (int) $b['day_of_week'] !== $t));
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
                                        'open' => 'Open',
                                        'break' => 'Break',
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
        // Authorization: ensure the profile belongs to the user
        if (auth()->id() !== $this->profile->user_id) {
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

        DB::transaction(function () use ($blocks) {
            // Helpers to work with HH:MM values as minutes
            $toM = function (string $hm): int {
                [$h, $m] = array_map('intval', explode(':', $hm));

                return $h * 60 + $m;
            };
            $toHM = function (int $mins): string {
                $h = intdiv($mins, 60);
                $m = $mins % 60;

                return str_pad((string) $h, 2, '0', STR_PAD_LEFT).':'.str_pad((string) $m, 2, '0', STR_PAD_LEFT);
            };

            // Merge intervals (overlap or touch) within an array of [start,end,label]
            $merge = function (array $items) use ($toM, $toHM): array {
                if (empty($items)) {
                    return [];
                }
                usort($items, fn ($a, $b) => $toM($a['start']) <=> $toM($b['start']));
                $merged = [];
                foreach ($items as $it) {
                    if (empty($merged)) {
                        $merged[] = $it;

                        continue;
                    }
                    $last = &$merged[count($merged) - 1];
                    $lastStart = $toM($last['start']);
                    $lastEnd = $toM($last['end']);
                    $curStart = $toM($it['start']);
                    $curEnd = $toM($it['end']);
                    // overlap or touch
                    if ($curStart <= $lastEnd) {
                        if ($curEnd > $lastEnd) {
                            $last['end'] = $toHM($curEnd);
                        }
                        // keep earliest label (prefer existing)
                    } else {
                        $merged[] = $it;
                    }
                    unset($last); // break the reference
                }

                return $merged;
            };

            // Wipe existing rows first
            $this->profile->operatingHours()->delete();

            // Group input by day and normalize
            $grouped = collect($blocks)->groupBy('day_of_week');
            foreach ($grouped as $day => $items) {
                // If the day has any 'closed' entry, persist a single closed row and skip others
                $hasClosed = collect($items)->contains(fn ($r) => ($r['block_type'] ?? null) === 'closed');
                if ($hasClosed) {
                    $this->profile->operatingHours()->create([
                        'day_of_week' => (int) $day,
                        'block_index' => 1,
                        'start_time' => null,
                        'end_time' => null,
                        'block_type' => 'closed',
                        'label' => null,
                    ]);

                    continue; // ignore any other blocks for this day
                }

                $day = (int) $day;
                $opens = [];
                $breaks = [];
                foreach ($items as $b) {
                    $row = [
                        'start' => $b['start_time'],
                        'end' => $b['end_time'],
                        'label' => $b['label'] ?? null,
                    ];
                    if ($b['block_type'] === 'break') {
                        $breaks[] = $row;
                    } else {
                        $opens[] = $row;
                    }
                }

                // Merge overlapping/touching intervals within each category
                $opens = $merge($opens);
                $breaks = $merge($breaks);

                // Subtract breaks from opens → produce final open segments
                $finalOpens = [];
                foreach ($opens as $op) {
                    $segStart = $toM($op['start']);
                    $segEnd = $toM($op['end']);
                    foreach ($breaks as $br) {
                        $brStart = $toM($br['start']);
                        $brEnd = $toM($br['end']);
                        // no overlap
                        if ($brEnd <= $segStart || $brStart >= $segEnd) {
                            continue;
                        }
                        // overlap: emit piece before break, then move start forward
                        if ($brStart > $segStart) {
                            $finalOpens[] = [
                                'start' => $toHM($segStart),
                                'end' => $toHM($brStart),
                                'label' => $op['label'] ?? null,
                            ];
                        }
                        $segStart = max($segStart, $brEnd);
                        if ($segStart >= $segEnd) {
                            break;
                        }
                    }
                    // leftover tail after last break
                    if ($segStart < $segEnd) {
                        $finalOpens[] = [
                            'start' => $toHM($segStart),
                            'end' => $toHM($segEnd),
                            'label' => $op['label'] ?? null,
                        ];
                    }
                }

                // Compose final blocks = (normalized breaks) + (split opens), sorted by start time
                $final = [];
                foreach ($breaks as $br) {
                    $final[] = [
                        'type' => 'break',
                        'start' => $br['start'],
                        'end' => $br['end'],
                        'label' => $br['label'] ?? null,
                    ];
                }
                foreach ($finalOpens as $op) {
                    $final[] = [
                        'type' => 'open',
                        'start' => $op['start'],
                        'end' => $op['end'],
                        'label' => $op['label'] ?? null,
                    ];
                }

                usort($final, fn ($a, $b) => $toM($a['start']) <=> $toM($b['start']));

                // Persist with sequential block_index
                $idx = 1;
                foreach ($final as $row) {
                    $this->profile->operatingHours()->create([
                        'day_of_week' => $day,
                        'block_index' => $idx++,
                        'start_time' => $row['start'],
                        'end_time' => $row['end'],
                        'block_type' => $row['type'],
                        'label' => $row['label'],
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
            'profile' => $this->profile,
        ];
    }
}
