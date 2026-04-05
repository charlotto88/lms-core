<?php

namespace App\Filament\Student\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class MyProfile extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static string $view = 'filament.student.pages.my-profile';

    protected static ?string $navigationLabel = 'My Profile';

    protected static ?string $title = 'Student Profile';

    public ?array $data = [];

    public function mount(): void
    {
        // Get the student record linked to the logged-in user
        $student = auth()->user()->student;

        if ($student) {
            $this->form->fill($student->attributesToArray());
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Section 1: Identity & Photo
                Section::make('Personal Identity')
                    ->description('Your official college identification and contact details.')
                    ->schema([
                        FileUpload::make('profile_photo')
                            ->avatar()
                            ->imageEditor()
                            ->directory('profile-photos')
                            ->columnSpanFull()
                            ->alignCenter(),

                        TextInput::make('first_name')
                            ->label('First Name')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('last_name')
                            ->label('Last Name')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('college_email')
                            ->label('Official College Email')
                            ->prefixIcon('heroicon-m-academic-cap')
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('This email is managed by the IT Department.'),

                        TextInput::make('phone')
                            ->label('Contact Number')
                            ->tel()
                            ->prefixIcon('heroicon-m-phone')
                            ->placeholder('e.g. 012 345 6789'),
                    ])->columns(2),

                // Section 2: Residential Details
                Section::make('Residential Information')
                    ->description('Current physical address for correspondence and deliveries.')
                    ->schema([
                        Textarea::make('physical_address')
                            ->label('Physical Address')
                            ->rows(3)
                            ->placeholder('Enter your full street address, suburb, and city...')
                            ->columnSpanFull(),
                    ]),

                // Section 3: Academic Record
                Section::make('Academic Record')
                    ->description('Official enrollment details (View Only).')
                    ->schema([
                        TextInput::make('student_id')
                            ->label('Student ID Number')
                            ->disabled()
                            ->dehydrated(false)
                            ->prefixIcon('heroicon-m-identification'),

                        DatePicker::make('enrollment_date')
                            ->label('Date of Enrollment')
                            ->disabled()
                            ->dehydrated(false)
                            ->displayFormat('d M Y'),

                        TextInput::make('status')
                            ->label('Account Status')
                            ->disabled()
                            ->dehydrated(false)
                            ->prefixIcon('heroicon-m-shield-check')
                            ->suffixIcon('heroicon-m-check-circle')
                            ->suffixIconColor('success') // This will force the icon to be green
                            ->hint('Current Standing')
                            ->hintColor('success')
                            ->extraInputAttributes(['class' => 'font-bold text-success-600']), // Tailwind class for green
                    ])->columns(3),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Update My Profile')
                ->icon('heroicon-m-check-circle')
                ->color('primary')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            
            // Update the student record
            auth()->user()->student->update($data);

            Notification::make()
                ->success()
                ->title('Profile Successfully Updated')
                ->body('Your changes have been saved to the college database.')
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Update Failed')
                ->body('There was an error saving your changes. Please contact the IT Admin.')
                ->send();
        }
    }
}