<?php

namespace App\Http\Controllers\Api\V1;

use App\DataTransferObjects\Product\ProductStoreDTO;
use App\DataTransferObjects\Product\ProductUpdateApiDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateApiRequest;
use App\Http\Resources\Api\V1\ProductCollectionsJsonResource;
use App\Http\Resources\Api\V1\ProductJsonResource;
use App\Models\Product;
use App\Repositories\CurrencyRepository;
use App\Repositories\ProductRepository;
use App\Services\CrudActionsServices\ProductServices\ProductApiService;
use App\Services\FilterTableService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ProductApiController extends Controller
{
    public function __construct(
        readonly ProductApiService $productApiService,
        readonly ProductRepository $productRepository,
        readonly CurrencyRepository $currencyRepository,
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, FilterTableService $filterTableService): ProductCollectionsJsonResource
    {
        try {
            $page = (int) $request->get(key: 'page', default: 1);
            $sortBy = trim($request->get(key: 'sort_by', default: 'id'));
            $sortWay = trim($request->get(key: 'sort_way', default: 'desc'));

            $filterFieldsArray = $filterTableService->processPrepareFilterFieldsArray(allFieldsData: $request->all());

            $products = $this->productRepository->getAllWithPaginate(perPage: 10, page: $page, orderBy: $sortBy, orderWay: $sortWay, filterFieldsData: $filterFieldsArray);

            return new ProductCollectionsJsonResource($products);
        } catch (Exception $exception) {
            Log::error('File: '.$exception->getFile().' ; Line: '.$exception->getLine().' ; Message: '.$exception->getMessage());
            abort(code: 500, message: __(key: 'Внутрішня помилка сервера'));
        }
    }

    /**
     *  Store a newly created resource in storage.
     *
     * @param ProductStoreRequest $productStoreRequest
     * @return JsonResponse
     */
    public function store(ProductStoreRequest $productStoreRequest): JsonResponse
    {
        try {
            $productStoreDTO = new ProductStoreDTO($productStoreRequest);

            $productModel = $this->productApiService->processStore(dto: $productStoreDTO);

            if ($productModel && $productModel->getKey() > 0) {
                /** @var Product $productModel */
                $productJsonResource = new ProductJsonResource($productModel);

                return $productJsonResource->response()->setStatusCode(Response::HTTP_CREATED);
            }

            return response()->json(data: ['error' => true, 'message' => __('Unable to create a Product due to incorrect data'),], status: Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $exception) {
            Log::error('File: '.$exception->getFile().' ; Line: '.$exception->getLine().' ; Message: '.$exception->getMessage());
            return response()->json(data: ['error' => true, 'message' => __('Something went wrong')], status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     *  Display the specified resource.
     *
     * @param string $id ID товара
     * @return ProductJsonResource|JsonResponse
     */
    public function show(string $id): JsonResponse|ProductJsonResource
    {
        try {
            if (is_numeric($id)) {
                $productModel = $this->productRepository->getForEditModel(id: (int) $id, useCache: true);

                if ($productModel && $productModel->getKey() > 0) {
                    /** @var Product $productModel */
                    $productJsonResource = new ProductJsonResource($productModel);

                    return $productJsonResource->response()->setStatusCode(Response::HTTP_OK);
                }
            }

            return response()->json(data: ['error' => true, 'message' => __('Product not found'), 'id' => (int) $id], status: Response::HTTP_NOT_FOUND);
        } catch (Exception $exception) {
            Log::error('File: '.$exception->getFile().' ; Line: '.$exception->getLine().' ; Message: '.$exception->getMessage());
            return response()->json(data: ['error' => true, 'message' => __('Something went wrong')], status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     *  Update the specified resource in storage.
     *
     * @param ProductUpdateApiRequest $productUpdateApiRequest
     * @param string $id ID товара
     * @return JsonResponse
     */
    public function update(ProductUpdateApiRequest $productUpdateApiRequest, string $id): JsonResponse
    {
        try {
            if (is_numeric($id)) {
                $productUpdateApiDTO = new ProductUpdateApiDTO(productUpdateApiRequest: $productUpdateApiRequest, product_id: (int) $id);

                $productModel = $this->productApiService->processUpdate(dto: $productUpdateApiDTO, repository: $this->productRepository);

                if ($productModel && $productModel->getKey() > 0) {
                    /** @var Product $productModel */
                    $productJsonResource = new ProductJsonResource($productModel);

                    return $productJsonResource->response()->setStatusCode(Response::HTTP_OK);
                }
            }

            return response()->json(data: ['error' => true, 'message' => __('Unable to update a Product due to incorrect data'),], status: Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $exception) {
            Log::error('File: '.$exception->getFile().' ; Line: '.$exception->getLine().' ; Message: '.$exception->getMessage());
            return response()->json(data: ['error' => true, 'message' => __('Something went wrong')], status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     *  Remove the specified resource from storage.
     *
     * @param string $id ID товара
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            if (is_numeric($id)) {
                $deleteProduct = $this->productApiService->processDestroy(id: (int) $id, repository: $this->productRepository);

                if ($deleteProduct) {
                    return response()->json(data: ['error' => false, 'message' => __('Product deleted successfully'),], status: Response::HTTP_NO_CONTENT);
                }
            }

            return response()->json(data: ['error' => true, 'message' => __('Unable to delete a Product'),], status: Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $exception) {
            Log::error('File: '.$exception->getFile().' ; Line: '.$exception->getLine().' ; Message: '.$exception->getMessage());
            return response()->json(data: ['error' => true, 'message' => __('Something went wrong')], status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
