<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Repositories\CurrencyRepository;
use App\Repositories\ProductRepository;
use App\Services\CrudActionsServices\ProductServices\ProductService;
use App\Services\FilterTableService;
use Exception;
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
     * @param Request $request
     * @param FilterTableService $filterTableService
     * @return \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Contracts\Foundation\Application
     */
    public function index(Request $request, FilterTableService $filterTableService): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $page = (int) $request->get('page', 1);
            $sortBy = (string) $request->get('sort_by', 'id');
            $sortWay = (string) $request->get('sort_way', 'desc');

            $filterFieldsArray = $filterTableService->processPrepareFilterFieldsArray($request->all());
            $filterFieldsObject = $filterTableService->processConvertFilterFieldsToObject($filterFieldsArray);

            $products = $this->productRepository->getAllWithPaginate(perPage: 2, page: $page, orderBy: $sortBy, orderWay: $sortWay, filterFieldsData: $filterFieldsArray);
            $displayedFields = $this->productRepository->getDisplayedFieldsOnIndexPage();

            $currenciesListData = $this->currencyRepository->getForDropdownList(fieldId: 'id', fieldName: 'iso_code', useCache: true);

            return view(view: 'admin.crud.product.index', data: compact(['products', 'displayedFields', 'filterFieldsObject', 'currenciesListData', 'sortBy', 'sortWay',]));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(data: ['error' => $exception->getMessage()], status: 401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
