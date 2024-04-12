<?php

namespace App\Observers\Product;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductClearCacheObserver
{
    /**
     *  Событие создания записи Модели
     *
     * @param Product $productModel
     * @return void
     */
    public function created(Product $productModel): void
    {
        Cache::forget($productModel::class.'-getModelCollection');
        Cache::forget($productModel::class.'-getForDropdownList');
    }

    /**
     *  Событие изменения записи Модели
     *
     * @param Product $productModel
     * @return void
     */
    public function updated(Product $productModel): void
    {
        Cache::forget($productModel::class.'-getModelCollection');
        Cache::forget($productModel::class.'-getForEditModel-'.$productModel->getKey());
        Cache::forget($productModel::class.'-getForDropdownList');
    }

    /**
     *  Событие удаления записи Модели
     *
     * @param Product $productModel
     * @return void
     */
    public function deleted(Product $productModel): void
    {
        Cache::forget($productModel::class.'-getModelCollection');
        Cache::forget($productModel::class.'-getForEditModel-'.$productModel->getKey());
        Cache::forget($productModel::class.'-getForDropdownList');
    }
}
