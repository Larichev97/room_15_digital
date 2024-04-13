@extends('layouts.app')

@section('content')
    <div class="container py-4">
        @include('components.breadcrumbs', ['title' => __('Додавання товару')])

        <form class="card p-4 mt-5" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')

            <a style="width: fit-content;" class="btn btn-dark" href="{{ route('products.index') }}">{{ __('Назад') }}</a>

            @include('components.alert')

            <div class="row">
                <div class="col-12 form-group mb-4">
                    <strong>{{ __('Назва') }} <span style="color: red">*</span></strong>
                    <input type="text" name="title" class="form-control mt-2 @error('title') is-invalid @enderror" placeholder="{{ __('Вкажіть назву товару...') }}" value="{{ old('title') }}">
                </div>
                <div class="col-12 form-group mb-4">
                    <strong>{{ __('Ціна') }}</strong>
                    <input type="text" name="price" class="form-control mt-2 @error('price') is-invalid @enderror" placeholder="{{ __('Вкажіть ціну товару...') }}" value="{{ old('price') }}">
                </div>
                <div class="col-12 form-group mb-4">
                    <strong>{{ __('Валюта') }} <span style="color: red">*</span></strong>
                    <br>
                    <select class="form-control mt-2 @error('currency_id') is-invalid @enderror" name="currency_id" id="currency_id">
                        <option value="0" {{ old('currency_id') == '0' ? 'selected' : '' }}>{{ __('Виберіть зі списку...') }}</option>
                        @if(!empty($currenciesListData))
                            @foreach($currenciesListData as $currencyItem)
                                <option value="{{ $currencyItem->id }}" {{ old('currency_id') == $currencyItem->id ? 'selected' : '' }}>
                                    {{ $currencyItem->iso_code }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg w-50">{{ __('Додати') }}</button>
                </div>
            </div>
        </form>
    </div>
@endsection
