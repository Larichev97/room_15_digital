@extends('layouts.app')

@section('content')
<div class="container py-4">
    @include('components.alert')

    <div class="row justify-content-center align-items-center">
        <div class="col-12">
            <div class="card" style="box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                <div class="card-header">{{ __('Головна') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('Ви увійшли в систему!') }}

                    <div class="row mt-4">
                        <div class="col-6 mb-xl-0 mb-4">
                            <a href="{{ route('products.index') }}" style="color: #f83033;">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="row" style="color: #f83033;">
                                            <div class="col-8">
                                                <p class="text-sm mb-0 text-uppercase fw-bold">{{ __('Товари') }}</p>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div class="icon icon-shape bg-gradient-dark shadow-primary text-center rounded-circle">
                                                    <i class="fa fa-list text-lg opacity-10" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 mb-xl-0 mb-4">
                            <a href="#" style="color: #f83033;">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="row" style="color: #f83033;">
                                            <div class="col-8">
                                                <p class="text-sm mb-0 text-uppercase fw-bold" style="color: #f83033;">{{ __('Валюти') }}</p>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div class="icon icon-shape bg-gradient-dark shadow-primary text-center rounded-circle">
                                                    <i class="fa fa-usd text-lg opacity-10" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
