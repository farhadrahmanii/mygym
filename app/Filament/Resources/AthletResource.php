<?php
namespace App\Filament\Resources;

use App\Filament\Resources\AthletResource\Pages;
use App\Filament\Resources\AthletResource\RelationManagers;
use App\Models\Athlet;
use App\Models\Fee;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AthletFeeExpiryNotification;
use App\Models\User;

class AthletResource extends Resource
{
    protected static ?string $model = Athlet::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $modelLabel = 'شاګرد ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('نام')
                        ->required()
                        ->maxLength(191),
                    Forms\Components\TextInput::make('father_name')
                        ->label('د پلار نوم')
                        ->required()
                        ->maxLength(191),
                    Forms\Components\TextInput::make('phone_number')
                        ->label('شماره تلفن')
                        ->tel()
                        ->required()
                        ->maxLength(191),
                    Forms\Components\FileUpload::make('photo')
                        ->label('تصویر')
                        ->imageEditor(),
                    Forms\Components\Select::make('admission_type')
                        ->label('نوع ثبت نام')
                        ->required()
                        ->options([
                            'gym' => 'GYM',
                            'fitness' => 'Fitness',
                        ])
                        ->default('gym')
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, $get) {
                            $boxId = $get('box_id') ?? null;
                            $fees = $state === 'gym' ? 500 : 1000;
                            if ($boxId) {
                                $fees += 150;
                            }
                            $set('fees', $fees);
                        }),

                    Forms\Components\Select::make('box_id')
                        ->label('صندق')
                        ->relationship('box', 'box_number')
                        ->searchable()
                        ->nullable()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('box_number')->required()->prefix('A-')->maxLength(191),
                            Forms\Components\DatePicker::make('expire_date')->required()->default(now()->addDays(30)),
                        ])->reactive()->afterStateUpdated(function ($state, callable $set, $get) {
                            $admissionType = $get('admission_type') ?? 'gym';
                            $fees = $admissionType === 'gym' ? 500 : 1000;
                            if ($state) {
                                $fees += 150;
                            }
                            $set('fees', $fees);
                            $set('updated_at', now());
                            $set('admission_expiry_date', now()->addDays(30));
                        })->rule(function ($get) {
                            return function ($attribute, $value, $fail) use ($get) {
                                if ($value) {
                                    $existingAthlet = Athlet::where('box_id', $value)->first();
                                    if ($existingAthlet && $existingAthlet->id !== $get('id')) {
                                        $fail('This box is already assigned to another athlete.');
                                    }
                                }
                            };
                        }),
                    Forms\Components\DatePicker::make('admission_expiry_date')
                        ->label('تاریخ ختم')
                        ->visible(fn($livewire) => $livewire instanceof Pages\EditAthlet),
                    Forms\Components\RichEditor::make('details')->label('تفصیل')->columnSpanFull(),
                ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('تصویر')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('نام')
                    ->searchable(),
                Tables\Columns\TextColumn::make('father_name')
                    ->label('د پلار نوم')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('شماره تلفن')
                    ->searchable(),
                Tables\Columns\TextColumn::make('admission_type')
                    ->label('بخش')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('admission_expiry_date')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(function ($record) {
                        $daysDifference = floor(Carbon::parse($record->admission_expiry_date)->diffInDays(now()));
                        return $daysDifference <= 10 && $daysDifference >= 0 ? 'danger' : ($daysDifference < 0 ? 'success' : 'warning');
                    })
                    ->label('روزهای ختم فیس')
                    ->getStateUsing(function ($record) {
                        $daysDifference = floor(Carbon::parse($record->admission_expiry_date)->diffInDays(now()));
                        return $daysDifference == 0 ? 'امروز' : ($daysDifference < 0 ? abs($daysDifference) . ' روز مانده' : $daysDifference . ' تیر شده');
                    }),
                Tables\Columns\TextColumn::make('days_since_created')
                    ->badge()
                    ->label('روزهای از ثبت')
                    ->color(fn(string $state): string => match ($state) {
                        'امروز' => 'success',
                        default => 'warning',
                    })
                    ->getStateUsing(fn($record) => Carbon::parse($record->created_at)->isToday() ? 'امروز' : floor(Carbon::parse($record->created_at)->diffInDays(now()))),
                Tables\Columns\TextColumn::make('box_id')
                    ->numeric()
                    ->prefix('#-')
                    ->badge()
                    ->size('sm')
                    ->fontFamily('mono')
                    ->label('صندق')
                    ->sortable(),
                Tables\Columns\TextColumn::make('this_month_fees')
                    ->label(' فیس این ماه')
                    ->getStateUsing(fn($record) => Fee::where('athlet_id', $record->id)->whereMonth('created_at', now()->month)->sum('fees'))
                    ->badge()
                    ->color(fn($state) => $state == 'هیچ نداده' ? 'danger' : 'warning')
                    ->getStateUsing(fn($record) => Fee::where('athlet_id', $record->id)->whereMonth('created_at', now()->month)->sum('fees') == 0 ? 'هیچ نداده' : Fee::where('athlet_id', $record->id)->whereMonth('created_at', now()->month)->sum('fees'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('fees')
                    ->label('فیس')
                    ->getStateUsing(fn($record) => Fee::where('athlet_id', $record->id)->sum('fees'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('admission_type')
                    ->label('نوع ثبت نام')
                    ->options([
                        'gym' => 'GYM',
                        'fitness' => 'Fitness',
                    ])
                    ->multiple(),
                Tables\Filters\Filter::make('athlet_expiry_past_10_days')
                    ->label('شاګردانی که فیس شان در ۱۰ روز ګذشته پوره شده است')
                    ->query(fn(Builder $query) => $query->whereBetween('admission_expiry_date', [now()->subDays(10), now()])),
                Tables\Filters\Filter::make('athlet_expiry_more_than_10_days')
                    ->label('شاګردانی که فیس شان بیشتر از 10 روز باقی مانده است')
                    ->query(fn(Builder $query) => $query->where('admission_expiry_date', '<=', now()->subDays(10))),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('collectFee')
                    ->label('جمع فیس و صندق')
                    ->button()
                    ->color('success')
                    ->action(function ($record, $data) {
                        $admissionType = $record->admission_type ?? 'gym';
                        $boxId = $data['box_id'] ?? null;
                        $fees = $data['fees'];
                        if ($boxId) {
                            $fees += 150;
                        }
                        Fee::create([
                            'athlet_id' => $record->id,
                            'fees' => $fees,
                        ]);
                        $record->admission_expiry_date = now()->addDays(30);
                        $record->box_id = $boxId;
                        $record->save();
                    })
                    ->form([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('fees')
                                ->label('فیس')
                                ->numeric(),
                            Forms\Components\Select::make('box_id')
                                ->label('صندق')
                                ->relationship('box', 'box_number')
                                ->searchable()
                                ->default(fn($record) => $record->box_id ?? null)
                                ->nullable()
                                ->createOptionForm([
                                    Forms\Components\Grid::make(2)->schema([
                                        Forms\Components\TextInput::make('box_number')
                                            ->required()
                                            ->label('د باکس نمبر')
                                            ->prefix('A-')
                                            ->maxLength(191),
                                        Forms\Components\DatePicker::make('expire_date')
                                            ->label('ختم تاریخ')
                                            ->required()
                                            ->default(now()->addDays(30)),
                                    ])
                                ])
                                ->reactive()
                                ->rule(function ($get) {
                                    return function ($attribute, $value, $fail) use ($get) {
                                        if ($value) {
                                            $existingAthlet = Athlet::where('box_id', $value)->first();
                                            if ($existingAthlet && $existingAthlet->id !== $get('id')) {
                                                $fail('This box is already assigned to another athlete.');
                                            }
                                        }
                                    };
                                }),
                        ]),
                    ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('totalfees')
                    ->label('تمام فیس جماوری شده: ' . Fee::whereMonth('created_at', now()->month)->sum('fees')),
                Tables\Actions\Action::make('totalAthletes')
                    ->label('تمام شاګردان که در این ماه ثبت نام کرده: ' . Athlet::whereMonth('created_at', now()->month)->count()),
                Tables\Actions\Action::make('notifyExpiringFees')
                    ->label('د فیس د ختم کیدو اعلامیه')
                    ->action(function () {
                        $athletes = Athlet::whereDate('admission_expiry_date', now()->addDay())->get();
                        $admin = User::where('role', 'admin')->first();
                        Notification::send($admin, new AthletFeeExpiryNotification($athletes));
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAthlets::route('/'),
            'create' => Pages\CreateAthlet::route('/create'),
            'view' => Pages\ViewAthlet::route('/{record}'),
            'edit' => Pages\EditAthlet::route('/{record}/edit'),
        ];
    }
}
