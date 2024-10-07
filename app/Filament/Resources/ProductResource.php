<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $navigationLabel = 'Products';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('Name'))
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true),

                                Forms\Components\TextInput::make('price')
                                    ->label(__('Price'))
                                    ->numeric()
                                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                    ->required(),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make(__('Images'))
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('media')
                                    ->collection('product-images')
                                    ->multiple()
                                    ->maxFiles(5)
                                    ->hiddenLabel(),
                            ])
                            ->collapsible(),
                    ])
                    ->columnSpan(['lg' => 2]),
            ])
            ->columns(2);
    }

    public static function getModelLabel(): string
    {
        return __('product');
    }

    public static function getNavigationLabel(): string
    {
        return __('Products');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query->withCount('reviews')
                    ->addSelect(['users_rating' => Review::select('rating')
                        ->whereColumn('product_id', 'products.id')
                        ->where('user_id', auth()->id())
                        ->limit(1)
                    ]);
            })
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('product-image')
                    ->label(__('Image'))
                    ->collection('product-images'),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label(__('Price'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('reviews_avg_rating')
                    ->label(__('Rating'))
                    ->avg('reviews', 'rating')
                    ->placeholder(__('No Reviews'))
                    ->formatStateUsing(function (float $state, Product $record): string {
                        return number_format($state, 1) + 0 . ' / 5 (' . $record->reviews_count . ' ' . __(Str::plural('review', $record->reviews_count)) . ')';
                    }),

                Tables\Columns\ViewColumn::make('users_rating')
                    ->label(__('Users rating'))
                    ->view('filament.tables.columns.users-rating'),
            ])
            ->recordUrl(null)
            ->filters([
                QueryBuilder::make()
                    ->constraints([
                        TextConstraint::make('name')
                            ->label(__('Name')),
                        NumberConstraint::make('price')
                            ->label(__('Price'))
                            ->icon('heroicon-m-currency-dollar'),
                    ])
                    ->constraintPickerColumns(2),
            ], layout: Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->deferFilters()
            ->actions([
                Tables\Actions\EditAction::make(),
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
