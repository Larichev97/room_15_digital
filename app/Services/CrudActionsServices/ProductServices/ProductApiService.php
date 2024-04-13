<?php

namespace App\Services\CrudActionsServices\ProductServices;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Models\Product;
use App\Repositories\CoreRepository;
use App\Services\CrudActionsServices\CoreCrudActionsApiService;
use Illuminate\Database\Eloquent\Model;

final class ProductApiService extends CoreCrudActionsApiService
{
    /**
     *  Создание записи о Товаре
     *
     * @param FormFieldsDtoInterface $dto
     * @return Model|false
     */
    public function processStore(FormFieldsDtoInterface $dto): Model|false
    {
        $formDataArray = $dto->getFormFieldsArray();

        // Other logic before create...

        $productModel = Product::query()->create(attributes: $formDataArray);

        if ($productModel) {
            // Other logic after update...

            return $productModel;
        }

        return false;
    }

    /**
     *  Обновление записи о Товаре
     *
     * @param FormFieldsDtoInterface $dto
     * @param CoreRepository $repository
     * @return Model|false
     */
    public function processUpdate(FormFieldsDtoInterface $dto, CoreRepository $repository): Model|false
    {
        $formDataArray = $dto->getFormFieldsArray();

        $idProduct = (int) $formDataArray['product_id'];

        if (!$idProduct) {
            return false;
        }

        $productModel = $repository->getForEditModel(id: $idProduct, useCache: true);

        if (empty($productModel)) {
            return false;
        }

        /** @var Product $productModel */

        // Other logic before update...

        $updateProduct = $productModel->update(attributes: $formDataArray);

        if (!$updateProduct) {
            return false;
        }

        // Other logic after update...

        return $productModel;
    }

    /**
     *  Полное удаление записи о Товаре
     *
     * @param int $id
     * @param CoreRepository $repository
     * @return bool
     */
    public function processDestroy(int $id, CoreRepository $repository): bool
    {
        $productModel = $repository->getForEditModel(id: (int) $id, useCache: true);

        if (!empty($productModel)) {
            /** @var Product $productModel */

            // Other logic before delete...

            $deleteProduct = (bool) $productModel->delete();

            if ($deleteProduct) {
                // Other logic after delete...
            }

            return $deleteProduct;
        }

        return false;
    }
}
