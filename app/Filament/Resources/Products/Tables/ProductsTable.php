<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use App\Models\Review;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ProductsTable
{
    public static function configure(Table $table): Table
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
                SpatieMediaLibraryImageColumn::make('product-image')
                    ->label(__('Image'))
                    ->collection('product-images'),
                    // ->conversion('thumb'),
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                    ->label(__('Price'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('reviews_avg_rating')
                    ->label(__('Rating'))
                    ->avg('reviews', 'rating')
                    ->placeholder(__('No Reviews'))
                    ->formatStateUsing(function (float $state, Product $record): string {
                        return number_format($state, 1) + 0 . ' / 5 (' . $record->reviews_count . ' ' . __(Str::plural('review', $record->reviews_count)) . ')';
                    }),
                ViewColumn::make('users_rating')
                    ->label(__('Users rating'))
                    ->view('filament.app.tables.columns.users-rating'),
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
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->deferFilters()
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
