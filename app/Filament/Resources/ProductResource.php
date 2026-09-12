<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-device-phone-mobile';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $state, callable $set) => $set('slug', Str::slug($state))),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug (URL)')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(4)
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('price')
                    ->label('Prix')
                    ->required()
                    ->numeric()
                    ->prefix('€')
                    ->minValue(0),

                Forms\Components\TextInput::make('compare_at_price')
                    ->label('Prix barré (avant promo)')
                    ->numeric()
                    ->prefix('€')
                    ->minValue(0)
                    ->nullable(),

                Forms\Components\TextInput::make('stock')
                    ->label('Stock')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),

                Forms\Components\TextInput::make('sku')
                    ->label('SKU (référence)')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Forms\Components\Select::make('category_id')
                    ->label('Catégorie')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('brand')
                    ->label('Marque')
                    ->maxLength(255)
                    ->nullable(),

                Forms\Components\Toggle::make('is_active')
                    ->label('Actif (visible sur le site)')
                    ->default(true),

                    Forms\Components\Repeater::make('translations')
    ->relationship()
    ->label('Traductions')
    ->schema([
        Forms\Components\Select::make('locale')
            ->label('Langue')
            ->options([
                'en' => 'English',
                'de' => 'Deutsch',
                'es' => 'Español',
            ])
            ->required(),

        Forms\Components\TextInput::make('name')
            ->label('Nom traduit')
            ->required()
            ->maxLength(255),

        Forms\Components\Textarea::make('description')
            ->label('Description traduite')
            ->rows(3),
    ])
    ->columns(1)
    ->columnSpanFull()
    ->defaultItems(0)
    ->addActionLabel('Ajouter une traduction'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Catégorie')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Prix')
                    ->money('EUR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->sortable()
                    ->color(fn (int $state): string => $state === 0 ? 'danger' : ($state < 5 ? 'warning' : 'success')),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Actif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}