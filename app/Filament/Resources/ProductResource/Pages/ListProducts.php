<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Review;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ListRecords;
use Mokhosh\FilamentRating\Components\Rating;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    public function leaveReviewAction(): Action
    {
        return Action::make('leaveReview')
            ->label(__('Leave review'))
            ->link()
            ->icon('heroicon-o-hand-thumb-up')
            ->form([
                Rating::make('rating')
                    ->label(__('Rating'))
                    ->default(5)
                    ->required(),
                Textarea::make('review')
                    ->label(__('Review')),
            ])
            ->action(function (array $data, array $arguments) {
                Review::create([
                    'user_id'    => auth()->id(),
                    'product_id' => $arguments['product'],
                    'rating'     => $data['rating'],
                    'review'     => $data['review'],
                ]);
            });
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
