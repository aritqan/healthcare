<!DOCTYPE html>

<html  lang="{{$_LOCALE_}}" dir="{{$_DIR_}}" >

	<head>
		<!--begin::Meta-->
        @include('admin::includes.meta')
        @stack('meta')
		<!--end::Meta-->

		<!--begin::Fonts-->
        @include('admin::includes.font')
        @stack('font')
		<!--end::Fonts-->

		<!--begin::styles-->
        @include('admin::includes.style')
        @stack('style')
		<!--end::styles-->
	</head>
	<!--end::Head-->

	<!--begin::Body-->
	<body id="kt_body" class="bg-body">

		<!--begin::Main-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root">
            <div class="d-flex flex-column flex-lg-row flex-column-fluid">
                <div class="d-flex flex-column flex-lg-row-fluid positon-xl-relative bgi-size-cover bgi-no-repeat" style="background-image:url('{{ asset('modules/admin/metronic/demo/media/patterns/background.jpg') }}')">
                </div>

                <div class="d-flex flex-column flex-lg-row-auto py-10">
                    <div class="menu menu-rounded menu-column menu-primary menu-state-bg fw-semibold w-100px" data-kt-menu="true">
                        <div class="menu-item" data-kt-menu-trigger="hover" data-kt-menu-placement="right-start">

                            @component('admin::components.other.hyperlink', [
                                    'options'       => [
                                        'menuLink'  => true,
                                    ]
                                ])
                                <span class="menu-title position-relative">
                                    {{$_CUR_LOCALE_NAME_}}
                                    @include('admin::components.other.image', [
                                        'options' => [
                                            'class' => 'w-25px h-25px rounded-1 ms-2',
                                            'src'   => config('admin.frontend.country_flag.current_local'),
                                            'alt'   => 'current local flag',
                                        ]
                                    ])
                                </span>
                            @endcomponent

                            <div class="menu-sub menu-sub-dropdown p-3 w-200px">
                                @include('admin::particles.languages.switcher')
                            </div>
                        </div>
                    </div>
                    @yield('mainContent')
                </div>
			</div>
		</div>
		<!--end::Root-->
		<!--end::Main-->

		<!--begin::Javascript-->
        @include('admin::includes.script')
        @include('admin::helpers.script.index')
        @stack('script')
		<!--end::Page Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>
