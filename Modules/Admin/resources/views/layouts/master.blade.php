<!DOCTYPE html>

<html lang="{{$_LOCALE_}}" dir="{{$_DIR_}}" >
	<!--begin::Head-->
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
	<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root" id="root-page">
			<!--begin::Page-->
			<div class="page d-flex flex-row flex-column-fluid">
				<!--begin::Aside-->
                @include('admin::includes.aside_menu.menu')
				<!--end::Aside-->

				<!--begin::Wrapper-->
				<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">

                    <!--begin::Header-->
                    @include('admin::includes.header')
                    <!--end::Header-->

					<!--begin::Content-->
					<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
						<!--begin::Toolbar-->
                        @yield('toolbar')
						<!--end::Toolbar-->


						<div class="post d-flex flex-column-fluid">
							<!--begin::Container-->
                            @yield('content')
							<!--end::Container-->
						</div>
					</div>
					<!--end::Content-->

					<!--begin::Footer-->
                    @include('admin::includes.footer')
					<!--end::Footer-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::Root-->


		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
            {!! config('admin.svgs.up_arrow') !!}
		</div>
		<!--end::Scrolltop-->

        <!--begin::Modal-->
        @yield('modal')
        <!--end::Modal-->

        <!--begin::Toast Notification-->
        @if(config('notification.enable_notification_in_admin_panel'))
            @include('admin::components.toasts.toast_notification')
        @endif
        <!--end::Toast Notification-->

		<!--begin::Javascript-->
        @include('admin::includes.script')
        @include('admin::helpers.script.index')
        @if(config('notification.enable_notification_in_admin_panel'))
            @include('notification::helpers.firebase_script')
        @endif

        @stack('beforeScript')
        @stack('script')
        @stack('afterScript')
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>
