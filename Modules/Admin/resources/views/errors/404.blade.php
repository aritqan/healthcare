@extends('admin::errors.layouts.custom')

@section('title', trans('admin::error.404_page.title'))

@section('content')
    @include('admin::components.other.image', [
        'options' => [
            'class' => 'illustration',
            'src'   => config('admin.frontend.error_pages.404'),
            'alt'   => '404 not found',
        ]
    ])
    <h1>
        @lang('admin::error.404_page.header')
    </h1>
    <p>
        @lang('admin::error.404_page.message')
    </p>
    <p>{!! trans('admin::error.footer') !!}</p>
@endsection
