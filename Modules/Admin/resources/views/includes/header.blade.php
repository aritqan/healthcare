<div id="kt_header" style="" class="header align-items-stretch">
    <!--begin::Container-->
    <div class="container-fluid d-flex align-items-stretch justify-content-between">

        <!--begin::Aside mobile toggle-->
        <div class="d-flex align-items-center d-lg-none ms-n2 me-2" title="Show aside menu">
            <div class="btn btn-icon  w-30px h-30px w-md-40px h-md-40px" id="kt_aside_mobile_toggle">
                {!! config('admin.svgs.burger_menu') !!}
            </div>
        </div>
        <!--end::Aside mobile toggle-->

        <!--begin::Mobile logo-->
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">

            @component('admin::components.other.hyperlink', [
                    'options'           => [
                        'class'         => 'd-lg-none',
                    ]
                ])
                @include('admin::components.other.image', [
                    'options'   => [
                        'class' => 'h-30px',
                        'src'   => config('admin.frontend.logos.header_mobile'),
                        'alt'   => 'Logo',
                    ]
                ])
            @endcomponent

        </div>
        <!--end::Mobile logo-->

        <!--begin::Wrapper-->
        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">

            <!--begin::Navbar-->
            <div class="d-flex align-items-stretch" id="kt_header_nav">
                <div class="header-menu align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
                    <!-- Left Side Components -->
                </div>
            </div>
            <!--end::Navbar-->

            <!--begin::Toolbar wrapper-->
            <div class="d-flex align-items-stretch flex-shrink-0">

                <!--begin::Theme mode-->
                <div class="d-flex align-items-center ms-1 ms-lg-3">
                    @component('admin::components.other.hyperlink', [
                            'options'           => [
                                'class'         => 'btn btn-icon btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px',
                                'onClick'       => 'toggleMode()',
                            ]
                        ])
                        <i class="fonticon-sun fs-2"></i>
                    @endcomponent
                </div>
                <!--end::Theme mode-->

                <!--begin::Notifications-->
                @if(config('notification.enable_notification_in_admin_panel'))
                    <div class="d-flex align-items-center ms-1 ms-lg-3">
                        <div id="notification-trigger" class="btn btn-icon btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px pulse pulse-success" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                            <span class="pulse-ring"></span>
                            <i class="fas fa-bell fs-2 position-relative">
                                <span id="unread-notifications-count" @class([
                                    'position-absolute top-0 start-0 translate-middle badge badge-circle badge-sm badge-danger',
                                    'd-none' => app('admin')->delivered_web_notifications_count == 0,
                                ])>
                                    <span id="belled-notifications-count">{{ app('admin')->delivered_web_notifications_count }}</span>
                                </span>
                            </i>
                        </div>
                        <div class="menu menu-sub menu-sub-dropdown menu-column notification-dropdown w-lg-375px" data-kt-menu="true">
                            <div class="d-flex bgi-position-center bgi-size-cover rounded-top align-items-baseline justify-content-between" style="background-image:url('{{ asset('modules/admin/metronic/demo/media/patterns/pattern-1.jpg') }}')">
                                <h3 class="text-white fw-bold px-9 mt-10 mb-6">
                                    @lang('admin::strings.notifications')
                                    <span class="fs-8 opacity-75 ps-3" id="notification-count">
                                        0 @lang('admin::strings.alerts')
                                    </span>
                                </h3>

                                <a href="#" id="mark-all-notifications-as-read" @class([
                                        'px-9',
                                        'd-none' => app('admin')->unread_web_notifications_count == 0,
                                    ])>
                                    <span class="text-success fw-bold text-decoration-underline">
                                        @lang('notification::notifications.mark_all_as_read')
                                    </span>
                                </a>
                            </div>
                            <div class="d-flex flex-column px-4">
                                <div class="scroll-y mh-325px my-5" id="notification-list"></div>
                                <div id="notification-list-empty" class="d-none">
                                    <p class="text-center text-muted">
                                        {!! trans('admin::strings.you_donot_have_any_notifications') !!}
                                    </p>
                                </div>
                                <div id="loading-spinner" class="d-none text-center my-5">
                                    <div class="spinner-border text-primary" role="status"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <!--end::Notifications-->

                <!--begin::User menu-->
                <div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
                    <div class="cursor-pointer symbol symbol-circle symbol-40px overflow-hidden me-3" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                        <div class="symbol-label">
                            <img src="{{ app('admin')->avatar_url }}" alt="{{ app('admin')->username }}" class="w-100" onerror="this.onerror=null; this.src='{{ asset('images/default/avatars/mr_admin.png') }}';"/>
                        </div>
                    </div>
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px" data-kt-menu="true">
                        <div class="menu-item px-3">
                            <div class="menu-content d-flex align-items-center px-3">
                                <div class="symbol symbol-50px  me-5">
                                    <img src="{{ app('admin')->avatar_url }}" alt="{{ app('admin')->username }}" class="w-100" onerror="this.onerror=null; this.src='{{ asset('images/default/avatars/mr_admin.png') }}';"/>
                                </div>
                                <div class="d-flex flex-column">
                                    <div class="fw-bolder d-flex align-items-center fs-5">
                                        {{ app('admin')->username }}
                                    </div>
                                    <a href="javascript:;" class="fw-bold text-muted text-hover-primary fs-7">
                                        {{ app('admin')->email_format }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="separator my-2"></div>

                        <div class="menu-item px-5">
                            @include('admin::components.other.hyperlink', [
                                'options'           => [
                                    'menuLink'      => true,
                                    'title'         => trans('admin::dashboard.header.my_profile'),
                                    'href'          => route('admin.profile.edit'),
                                ]
                            ])
                        </div>

                        <div class="separator my-2"></div>

                        <div class="menu-item px-5" data-kt-menu-trigger="hover" data-kt-menu-placement="left-start">
                            @component('admin::components.other.hyperlink', [
                                    'options'           => [
                                        'menuLink'      => true,
                                    ]
                                ])
                                <span class="menu-title position-relative">
                                    @lang('admin::dashboard.header.language')
                                    <span class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">
                                        {{$_CUR_LOCALE_NAME_}}
                                        @include('admin::components.other.image', [
                                            'options' => [
                                                'class' => 'w-15px h-15px rounded-1 ms-2',
                                                'src'   => config('admin.frontend.country_flag.current_local'),
                                                'alt'   => 'current local flag',
                                            ]
                                        ])
                                    </span>
                                </span>

                            @endcomponent

                            <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                @include('admin::particles.languages.switcher')
                            </div>
                        </div>
                        @session('OLD_ADMIN_ID')
                            <div class="menu-item px-5">
                                @include('admin::components.other.hyperlink', [
                                    'options'                   => [
                                        'id'                    => 'back-to-preivos-account',
                                        'title'                 => trans('admin::dashboard.header.back_to_preivos_account'),
                                        'href'                  => route('admin.profile.backToOldAccount'),
                                        'menuLink'              => true,
                                        'withConfirmDialog'     => false,
                                        'class'                 => 'text-danger',
                                        'method'                => 'POST',
                                        'isAjax'                => true,
                                    ]
                                ])
                            </div>
                        @endsession
                        <div class="menu-item px-5">
                            @include('admin::components.other.hyperlink', [
                                'options'                   => [
                                    'id'                    => 'logout',
                                    'title'                 => trans('admin::dashboard.header.logout'),
                                    'href'                  => route('admin.auth.logout'),
                                    'menuLink'              => true,
                                    'withConfirmDialog'     => true,
                                    'showCanceledDialog'    => false,
                                    'dialogTitle'           => trans('admin::confirmations.confirm.logout.title'),
                                    'dialogDesc'            => '',
                                    'dialogConfirmButton'   => trans('admin::confirmations.confirm.logout.confirm_btn'),
                                    'dialogCancelButton'    => trans('admin::confirmations.cancel'),

                                ]
                            ])
                        </div>
                    </div>
                </div>
                <!--end::User menu-->
            </div>
            <!--end::Toolbar wrapper-->
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Container-->
</div>
