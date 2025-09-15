<?php

namespace App\Filament\Merchant\Pages;

use App\Models\MerchantProfile;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Arr;

class MyProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'My Profile';
    protected static ?string $navigationGroup = 'Account Management';
    protected static ?int $navigationSort = 10;
    protected static ?string $title = 'My Merchant Profile';

    /**
     * Blade view path for this page.
     * Ensure you have: resources/views/filament/merchant/pages/my-profile.blade.php
     */
    protected static string $view = 'filament.merchant.pages.my-profile';

    /**
     * Form state container.
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    /** @var MerchantProfile */
    public MerchantProfile $profile;

    /** Toggle to enable/disable editing mode */
    public bool $isEditing = false;

    public function mount(): void
    {
        // Must be logged in on the merchant panel
        $user = auth()->user();
        if (! $user) {
            // Let Filament handle auth redirection
            $this->redirect(route('filament.merchant.auth.login'));
            return;
        }

        // Load the merchant profile via relation
        $this->profile = $user->merchantProfile;

        if (! $this->profile) {
            // No profile yet – show a friendly message and send them somewhere safe
            Notification::make()
                ->title('No merchant profile found for your account')
                ->body('Please wait for admin approval or contact support.')
                ->warning()
                ->send();

            $this->redirect(route('filament.merchant.pages.dashboard'));
            return;
        }

        // Pre-fill form with current values
        $this->form->fill([
            'name'                => $this->profile->name,
            'phone'               => $this->profile->phone,
            'address'             => $this->profile->address,
            'registration_number' => $this->profile->registration_number,
            'license_number'      => $this->profile->license_number,
            'document_path'       => $this->profile->document_path,
            'photo'               => $this->profile->photo,
            'description'         => $this->profile->description,
        ]);
        $this->isEditing = false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->model($this->profile) // Eloquent model binding (ORM via PDO)
            ->statePath('data')
            ->schema([
                Section::make('Profile')
                    ->columns(2)
                    ->headerActions([
                        FormAction::make('editProfile')
                            ->label('Edit')
                            ->icon('heroicon-o-pencil-square')
                            ->action('startEdit')
                            ->visible(fn (): bool => ! $this->isEditing),
                        FormAction::make('saveProfile')
                            ->label('Save changes')
                            ->icon('heroicon-o-check')
                            ->color('success')
                            ->action('save')
                            ->requiresConfirmation()
                            ->visible(fn (): bool => $this->isEditing),
                        FormAction::make('cancelProfile')
                            ->label('Cancel')
                            ->icon('heroicon-o-x-mark')
                            ->color('gray')
                            ->action('cancelEdit')
                            ->visible(fn (): bool => $this->isEditing),
                    ])
                    ->schema([
                        TextInput::make('name')
                            ->label('Merchant Name')
                            ->disabled(fn () => ! $this->isEditing)
                            ->required()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->tel()
                            ->disabled(fn () => ! $this->isEditing)
                            ->maxLength(30),

                        Textarea::make('address')
                            ->rows(3)
                            ->disabled(fn () => ! $this->isEditing)
                            ->columnSpanFull(),
                        
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->disabled(fn () => ! $this->isEditing)
                            ->columnSpanFull(),

                        FileUpload::make('photo')
                            ->label('Photo')
                            ->image()
                            ->disk('public')
                            ->directory('merchant_photos')
                            ->downloadable()
                            ->openable()
                            ->disabled(fn () => ! $this->isEditing)
                            ->columnSpanFull(),
                    ]),

                Section::make('Compliance')
                    ->columns(2)
                    ->schema([
                        TextInput::make('registration_number')
                            ->label('Registration Number')
                            ->disabled(),

                        TextInput::make('license_number')
                            ->label('License Number')
                            ->disabled(),

                        FileUpload::make('document_path')
                            ->label('Document')
                            ->disk('public')
                            ->directory('merchant_docs')
                            ->downloadable()
                            ->openable()
                            ->disabled()
                            ->columnSpanFull()
                            ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'])
                            ->maxSize(5120)
                    ]),
            ]);
    }

    /** Single Save button under the form */
    protected function getFormActions(): array
    {
        return [];
    }

    /** Enable edit mode */
    public function startEdit(): void
    {
        $this->isEditing = true;
    }

    /** Cancel edit mode and reset the form back to model values */
    public function cancelEdit(): void
    {
        $this->isEditing = false;
        $this->form->fill([
            'name'                => $this->profile->name,
            'phone'               => $this->profile->phone,
            'address'             => $this->profile->address,
            'registration_number' => $this->profile->registration_number,
            'license_number'      => $this->profile->license_number,
            'document_path'       => $this->profile->document_path,
            'photo'               => $this->profile->photo,
            'description'         => $this->profile->description,
        ]);
    }

    /** Validate and persist changes */
    public function save(): void
    {
        $data = Arr::only($this->form->getState(), [
            'name', 'phone', 'address', 'description', 'photo',
        ]);

        // Extra guard: ensure the profile belongs to the logged-in user
        if (auth()->id() !== $this->profile->user_id) {
            Notification::make()->title('Unauthorized')->danger()->body('You cannot edit this profile.')->send();
            return;
        }

        $this->profile->update($data);

        $this->isEditing = false;

        Notification::make()
            ->title('Profile updated')
            ->success()
            ->body('Your merchant profile has been saved successfully.')
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
