@extends('admin::errors.layouts.custom')

@section('title', trans('admin::error.500_page.title'))

@section('content')
    @include('admin::components.other.image', [
        'options' => [
            'class' => 'illustration',
            'src'   => config('admin.frontend.error_pages.500'),
            'alt'   => '500 server error',
        ]
    ])
    <h1>
        @lang('admin::error.500_page.header')
    </h1>
    <p>
        @lang('admin::error.500_page.message')
    </p>
    <p>{!! trans('admin::error.footer') !!}</p>
@endsection
