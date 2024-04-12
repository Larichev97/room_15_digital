<?php

namespace App\Services\CrudActionsServices\ProductServices;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Models\Product;
use App\Repositories\CoreRepository;
use App\Services\CrudActionsServices\CoreCrudActionsService;

final class ProductService extends CoreCrudActionsService
{
    /**
     *  Создание записи о Товаре
     *
     * @param FormFieldsDtoInterface $dto
     * @return bool
     */
    public function processStore(FormFieldsDtoInterface $dto): bool
    {
        $formDataArray = $dto->getFormFieldsArray();

        // Other logic before create...

        $productModel = Product::query()->create(attributes: $formDataArray);

        if ($productModel) {
            // Other logic after update...

            return true;
        }

        return false;
    }

    /**
     *  Обновление записи о Товаре
     *
     * @param FormFieldsDtoInterface $dto
     * @param CoreRepository $repository
     * @return bool
     */
    public function processUpdate(FormFieldsDtoInterface $dto, CoreRepository $repository): bool
    {
        $formDataArray = $dto->getFormFieldsArray();

        $idProduct = (int) $formDataArray['product_id'];

        if (!$idProduct) {
            return false;
        }

        $productModel = $repository->getForEditModel(id:  $idProduct, useCache: true);

        if (empty($productModel)) {
            return false;
        }

        /** @var Product $productModel */

        // Other logic before update...

        $updateProduct = (bool) $productModel->update(attributes: $formDataArray);

        if ($updateProduct) {
            // Other logic after update...
        }

        return $updateProduct;
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
