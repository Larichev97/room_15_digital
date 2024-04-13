<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\Product\ProductStoreDTO;
use App\DataTransferObjects\Product\ProductUpdateDTO;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Repositories\CurrencyRepository;
use App\Repositories\ProductRepository;
use App\Services\CrudActionsServices\ProductServices\ProductService;
use App\Services\FilterTableService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class ProductController extends Controller
{
    /**
     * @param ProductService $productService
     * @param ProductRepository $productRepository
     * @param CurrencyRepository $currencyRepository
     */
    public function __construct(
        readonly ProductService $productService,
        readonly ProductRepository $productRepository,
        readonly CurrencyRepository $currencyRepository,
    )
    {
    }

    /**
     *  Display a listing of the resource.
     *
     * @param Request $request
     * @param FilterTableService $filterTableService
     * @return View|Application|Factory|JsonResponse|\Illuminate\Contracts\Foundation\Application
     */
    public function index(Request $request, FilterTableService $filterTableService): View|Application|Factory|JsonResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $page = (int) $request->get(key: 'page', default: 1);
            $sortBy = trim($request->get(key: 'sort_by', default: 'id'));
            $sortWay = trim($request->get(key: 'sort_way', default: 'desc'));

            $filterFieldsArray = $filterTableService->processPrepareFilterFieldsArray(allFieldsData: $request->all());
            $filterFieldsObject = $filterTableService->processConvertFilterFieldsToObject(filterFieldsArray: $filterFieldsArray);

            $products = $this->productRepository->getAllWithPaginate(perPage: 10, page: $page, orderBy: $sortBy, orderWay: $sortWay, filterFieldsData: $filterFieldsArray);
            $displayedFields = $this->productRepository->getDisplayedFieldsOnIndexPage();

            $currenciesListData = $this->currencyRepository->getForDropdownList(fieldId: 'id', fieldName: 'iso_code', useCache: true);

            return view(view: 'admin.crud.product.index', data: compact(['products', 'displayedFields', 'filterFieldsObject', 'currenciesListData', 'sortBy', 'sortWay',]));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            abort(code: 500, message: __(key: 'Внутрішня помилка сервера'));
        }
    }

    /**
     *  Show the form for creating a new resource.
     *
     * @return Factory|Application|View|\Illuminate\Contracts\Foundation\Application
     */
    public function create(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $currenciesListData = $this->currencyRepository->getForDropdownList(fieldId: 'id', fieldName: 'iso_code', useCache: true);

        return view(view: 'admin.crud.product.create', data: compact(['currenciesListData',]));
    }

    /**
     *  Store a newly created resource in storage.
     *
     * @param ProductStoreRequest $productStoreRequest
     * @return RedirectResponse|void
     */
    public function store(ProductStoreRequest $productStoreRequest)
    {
        try {
            $productStoreDTO = new ProductStoreDTO($productStoreRequest);

            $createProduct = $this->productService->processStore(dto: $productStoreDTO);

            if ($createProduct) {
                return redirect()->route(route: 'products.index')->with(key: 'success', value: 'Товар успішно додано.');
            }

            return back()->with(key: 'error', value: 'Помилка! Товар не додано.')->withInput();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            abort(code: 500, message: __(key: 'Внутрішня помилка сервера'));
        }
    }

    /**
     *  Display the specified resource.
     *
     * @param int $id ID товара
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View|Application|void
     */
    public function show(int $id)
    {
        try {
            $product = $this->productRepository->getForEditModel(id: $id, useCache: true);

            if (!$product) {
                abort(code: 404, message: __(key: 'Товар не знайдено'));
            }

            return view(view: 'admin.crud.product.show', data: compact(['product',]));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            abort(code: 500, message: __(key: 'Внутрішня помилка сервера'));
        }
    }

    /**
     *  Show the form for editing the specified resource.
     *
     * @param int $id ID товара
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View|Application|void
     */
    public function edit(int $id)
    {
        try {
            $product = $this->productRepository->getForEditModel(id: $id, useCache: true);

            if (!$product) {
                abort(code: 404, message: __(key: 'Товар не знайдено'));
            }

            $currenciesListData = $this->currencyRepository->getForDropdownList(fieldId: 'id', fieldName: 'iso_code', useCache: true);

            return view(view: 'admin.crud.product.edit', data: compact(['product', 'currenciesListData',]));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            abort(code: 500, message: __(key: 'Внутрішня помилка сервера'));
        }
    }

    /**
     *  Update the specified resource in storage.
     *
     * @param ProductUpdateRequest $productUpdateRequest
     * @param int $id ID товара
     * @return RedirectResponse|void
     */
    public function update(ProductUpdateRequest $productUpdateRequest, int $id)
    {
        try {
            $productUpdateDTO = new ProductUpdateDTO(productUpdateRequest: $productUpdateRequest, product_id: $id);

            $updateProduct = $this->productService->processUpdate(dto: $productUpdateDTO, repository: $this->productRepository);

            if ($updateProduct) {
                return redirect()->route(route: 'products.index')->with(key: 'success', value: sprintf(__('Дані товару #%s успішно оновлено.'), $id));
            }

            return back()->with(key: 'error', value: sprintf(__('Помилка! Дані товару #%s не оновлено.'), $id))->withInput();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            abort(code: 500, message: __(key: 'Внутрішня помилка сервера'));
        }
    }

    /**
     *  Remove the specified resource from storage.
     *
     * @param int $id ID товара
     * @return RedirectResponse|void
     */
    public function destroy(int $id)
    {
        try {
            $deleteProduct = $this->productService->processDestroy(id: $id, repository: $this->productRepository);

            if ($deleteProduct) {
                return redirect()->route(route: 'products.index')->with(key: 'success', value: sprintf(__('Товар #%s успішно видалено.'), $id));
            }

            return back()->with(key: 'error', value: sprintf(__('Помилка! Товар #%s не видалено.'), $id));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            abort(code: 500, message: __(key: 'Внутрішня помилка сервера'));
        }
    }
}
