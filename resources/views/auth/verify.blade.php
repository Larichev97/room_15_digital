@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Підтвердіть свою адресу електронної пошти') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Свіже посилання для підтвердження було надіслано на вашу електронну пошту.') }}
                        </div>
                    @endif

                    {{ __('Перш ніж продовжити, будь ласка, перевірте свою електронну пошту на наявність посилання для підтвердження.') }}
                    {{ __('Якщо ви не отримали листа на пошту') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('натисніть тут, щоб запросити ще один') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
