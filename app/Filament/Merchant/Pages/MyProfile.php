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
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class MyProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'My Profile';
    protected static ?string $title = 'My Merchant Profile';
    protected static ?string $navigationGroup = 'Account';

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
    }

    public function form(Form $form): Form
    {
        return $form
            ->model($this->profile) // Eloquent model binding (ORM via PDO)
            ->statePath('data')
            ->schema([
                Section::make('Profile')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Merchant Name')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('phone')
                            ->tel()
                            ->disabled()
                            ->dehydrated(false),

                        Textarea::make('address')
                            ->rows(3)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                        
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),

                        FileUpload::make('photo')
                            ->label('Photo')
                            ->image()
                            ->directory('merchant-photos')
                            ->downloadable()
                            ->openable()
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ]),

                Section::make('Compliance')
                    ->columns(2)
                    ->schema([
                        TextInput::make('registration_number')
                            ->label('Registration Number')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('license_number')
                            ->label('License Number')
                            ->disabled()
                            ->dehydrated(false),

                        FileUpload::make('document_path')
                            ->label('Document')
                            ->disk('public')
                            ->directory('merchant_docs')
                            ->downloadable()
                            ->openable()
                            ->disabled()
                            ->dehydrated(false)
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
}
