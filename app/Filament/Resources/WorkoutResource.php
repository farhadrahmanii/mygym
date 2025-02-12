<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkoutResource\Pages;
use App\Models\Workout;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Ruelluna\CanvasPointer\CanvasPointer;
use RuelLuna\CanvasPointer\Forms\Components\CanvasPointerField;

class WorkoutResource extends Resource
{
    protected static ?string $model = Workout::class;
    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $navigationLabel = 'ترینینگ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('title')
                        ->label('Workout Title')
                        ->required()
                        ->maxLength(191),
                    FileUpload::make('video')
                        ->label('Workout Video')
                        ->required()
                        ->acceptedFileTypes(['video/*']),
                    CanvasPointerField::make('body_part')
                        ->label('Select Body Part')
                        ->pointRadius(5) // default is 5
                        ->width(400) // required
                        ->height(400) // required
                        ->label('Select body parts that are in pain')
                        ->imageUrl(url('images/body.png')),
                    TextInput::make('description')
                        ->label('Workout Description')
                        ->required()
                        ->maxLength(500),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('body_part')
                    ->label('Body Part')
                    ->searchable(),
                TextColumn::make('title')
                    ->label('Workout Title')
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Workout Description')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                // Add any necessary filters here
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Add any necessary relations here
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkouts::route('/'),
            'create' => Pages\CreateWorkout::route('/create'),
            'view' => Pages\ViewWorkout::route('/{record}'),
            'edit' => Pages\EditWorkout::route('/{record}/edit'),
        ];
    }
}
