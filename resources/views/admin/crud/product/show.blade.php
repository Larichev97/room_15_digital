@extends('layouts.app')

@section('content')
    <div class="container py-4">
        @include('components.breadcrumbs', ['title' => __('Перегляд товару')])

        <div class="card mt-5" style="box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6"><h5>{{ __('Інформація про товар') }}</h5></div>
                    <div class="col-md-6 d-flex align-items-center">
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary ms-auto">{{ __('Редагувати') }}</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 form-group mb-4">
                        <label for="product_title" class="form-control-label fw-bold">{{ __('Назва') }}</label>
                        <input disabled="disabled" class="form-control mt-2" type="text" id="product_title" name="title" value="{{ old('title', $product->title) }}">
                    </div>
                    <div class="col-12 form-group mb-4">
                        <label for="product_price" class="form-control-label fw-bold">{{ __('Ціна') }}</label>
                        <input disabled="disabled" class="form-control mt-2" type="text" id="product_price" name="price" value="{{ old('price', $product->price) }}">
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-4">
                            <label for="product_currency_id" class="form-control-label fw-bold">{{ __('Валюта') }}</label>
                            <input disabled="disabled" class="form-control mt-2" type="text" id="product_currency_id" name="currency_id" value="{{ $product->currency->iso_code }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
