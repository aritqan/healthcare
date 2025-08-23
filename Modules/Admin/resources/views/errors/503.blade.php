@extends('admin::errors.layouts.custom')

@section('title', trans('admin::error.503_page.title'))

@section('content')
    @include('admin::components.other.image', [
        'options' => [
            'class' => 'illustration',
            'src'   => config('admin.frontend.error_pages.503'),
            'alt'   => '503 under maintenance',
        ]
    ])
    <h1>
        @lang('admin::error.503_page.header')
    </h1>
    <p>
        @lang('admin::error.503_page.message')
    </p>
    {{-- <p>{!! trans('admin::error.footer') !!}</p> --}}
@endsection
