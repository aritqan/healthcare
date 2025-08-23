@extends('admin::errors.layouts.custom')

@section('title', trans('admin::error.405_page.title'))

@section('content')
    @include('admin::components.other.image', [
        'options' => [
            'class' => 'illustration',
            'src'   => config('admin.frontend.error_pages.405'),
            'alt'   => '405 method not allowed',
        ]
    ])
    <h1>
        @lang('admin::error.405_page.header')
    </h1>
    <p>
        @lang('admin::error.405_page.message')
    </p>
    <p>{!! trans('admin::error.footer') !!}</p>
@endsection
