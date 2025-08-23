@php
    $title = isset($title) ? trans('admin::meta.main_title') . ' - ' .  $title : trans('admin::meta.main_title')
@endphp

<title>
    {{$title}}
</title>
<meta charset="utf-8" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<!--begin::shortcut icon-->
<link rel="shortcut icon" href="{{config('admin.frontend.logos.fav_icon')}}" />
<!--end::shortcut icon-->
