@extends('layouts.app')

@section('content')
    <div class="container py-4">
        @include('components.breadcrumbs', ['title' => __('Товари')])
        @include('components.alert')

        <div class="row justify-content-center align-items-center">
            <div class="col-12">
                <div class="card mb-4" style="box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                    <div class="card-header">
                        <div class="row mb-2">
                            <div class="col-4">
                                <h5>{{ __('Список товарів') }}</h5>
                            </div>
                            <div class="col-8 d-flex">
                                <a class="btn btn-success" href="{{ route('products.create') }}" style="margin-left: auto">{{ __('Додати товар') }}</a>
                                <a class="btn btn-danger" href="{{ route('products.index') }}" style="margin-left: 15px;">{{ __('Очистити фільтр') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        @if(!empty($products) && $products->total() > 0 && !empty($displayedFields) && is_array($displayedFields))
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        @include('components.table-with-data.table-filter-header', ['indexRouteName' => 'products.index'])
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <form id="products_filter_form" action="{{ route('products.index') }}" method="GET">
                                                <input type="hidden" name="sort_by" value="{{ $sortBy }}">
                                                <input type="hidden" name="sort_way" value="{{ $sortWay }}">

                                                @php $isObjectFilterFiles = (isset($filterFieldsObject) && is_object($filterFieldsObject)); @endphp

                                                @foreach($displayedFields as $displayedFieldArray)
                                                    @if(!empty($displayedFieldArray) && is_array($displayedFieldArray))
                                                    <th class="text-center font-weight-bolder">
                                                        @php $currentFieldName = (string) $displayedFieldArray['field']; @endphp

                                                        {{-- Выпадающий список --}}
                                                        @if('select' === $displayedFieldArray['field_input_type'])
                                                            <select class="form-control" name="filter_{{ $currentFieldName }}" id="filter_{{ $currentFieldName }}">
                                                                <option value="0" @if($isObjectFilterFiles && empty($filterFieldsObject->$currentFieldName)) selected="selected" @endif>{{ __('Виберіть зі списку...') }}</option>

                                                                @if('currency_id' === $currentFieldName && !empty($currenciesListData))
                                                                    @foreach($currenciesListData as $currencyItem)
                                                                        <option value="{{ $currencyItem->id }}" @if($isObjectFilterFiles && !empty($filterFieldsObject->$currentFieldName) && (int) $filterFieldsObject->$currentFieldName == (int) $currencyItem->id) selected="selected" @endif>
                                                                            {{ $currencyItem->iso_code }}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        @else
                                                            <input type="{{ $displayedFieldArray['field_input_type'] }}" name="filter_{{ $currentFieldName }}" id="filter_{{ $currentFieldName }}" class="form-control" value="@if($isObjectFilterFiles && !empty($filterFieldsObject->$currentFieldName)){{ $filterFieldsObject->$currentFieldName }}@endif">
                                                        @endif
                                                    </th>
                                                    @endif
                                                @endforeach

                                                <th class="text-center text-secondary font-weight-bolder">
                                                    <button type="submit" class="btn btn-info" style="margin-bottom: 0;">{{ __('Фільтр') }}</button>
                                                </th>
                                            </form>
                                        </tr>
                                        @foreach ($products as $product)
                                            <tr>
                                                @foreach($displayedFields as $displayedFieldArray)
                                                    @php $currentFieldName = (string) $displayedFieldArray['field']; @endphp

                                                    {{-- Уникальный вывод значения у поля: --}}
                                                    @if('currency_id' === $currentFieldName)
                                                        <td class="align-middle text-center">{{ $product->currency->iso_code }}</td>
                                                    {{-- Вывод значения из свойства модели: --}}
                                                    @else
                                                        <td class="align-middle text-center">{{ $product->$currentFieldName }}</td>
                                                    @endif
                                                @endforeach

                                                @include('components.table-with-data.table-row-actions', ['entityId' => $product->id, 'destroyRouteName' => 'products.destroy', 'showRouteName' => 'products.show', 'editRouteName' => 'products.edit',])
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                {!! $products->links('components.pagination') !!}
                            </div>
                        @else
                            <div class="p-3">
                                <h4>{{ __('Товарів немає') }}</h4>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    @include('components.filter-script', ['idFilterForm' => 'products_filter_form'])
@endpush
