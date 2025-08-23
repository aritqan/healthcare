@php
    use Modules\Notification\Enums\NotificationChannels;
@endphp

@extends('admin::layouts.master', ['title' => trans('admin::cruds.notifications.add')])

@section('toolbar')
    @include('admin::includes.toolbar', [
        'options'               => [
            'title'             => trans('admin::dashboard.aside_menu.notification_management.notifications'),
            'backUrl'           => route('notification.notifications.index'),
            'saveTitle'         => trans('admin::base.send'),
            'actions'           => [
                'save'          => true,
                'back'          => true,
            ],
        ]
    ])
@endsection

@section('content')
    <div id="kt_content_container" class="container-fluid">
        <div class="row g-5">
            <div class="col-lg-3"></div>

            <div class="col-xxl-6 col-12">
                <div class="card card-bordered mb-5">
                    <div class="card-header">
                        <h3 class="card-title">
                            @lang('admin::cruds.notifications.add')
                        </h3>
                    </div>
                    <div class="card-body">
                        @component('admin::components.forms.form', [
                                'options'       => [
                                    'isAjax'    => true,
                                    'action'    => route('notification.notifications.postCreate'),
                                ]
                            ])
                            @slot('fields')
                                <div class="row">
                                    <div class="col-12 mb-10 form-group">
                                        @include('admin::components.inputs.select', [
                                            'options'           => [
                                                'name'          => 'group',
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.notification_crud.groups.label'),
                                                'help'          => trans('admin::inputs.notification_crud.groups.help'),
                                                'data'          => $systemMainRoles,
                                                'text'          => function($key, $value) {return trans('permission::seeder.main_roles.' . $value);},
                                                'values'        => function($key, $value) {return $key;},
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-10 form-group user-list">
                                        @include('admin::components.inputs.select', [
                                            'options'           => [
                                                'id'            => 'users_id',
                                                'name'          => 'users_id',
                                                'label'         => trans('admin::inputs.notification_crud.users.label'),
                                                'placeholder'   => trans('admin::inputs.notification_crud.users.placeholder'),
                                                'help'          => trans('admin::inputs.notification_crud.users.subText'),
                                                'subText'       => trans('admin::inputs.notification_crud.users.subText'),
                                                'url'           => route('auth.users.ajaxList'),
                                                'clearable'     => true,
                                                'isAjax'        => true,
                                                'multiple'      => true,
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-10 form-group admin-list" @style(['display:none'])>
                                        @include('admin::components.inputs.select', [
                                            'options'           => [
                                                'id'            => 'admins_id',
                                                'name'          => 'users_id',
                                                'label'         => trans('admin::inputs.notification_crud.admins.label'),
                                                'placeholder'   => trans('admin::inputs.notification_crud.admins.placeholder'),
                                                'help'          => trans('admin::inputs.notification_crud.admins.subText'),
                                                'subText'       => trans('admin::inputs.notification_crud.admins.subText'),
                                                'url'           => route('admin.admins.ajaxList'),
                                                'clearable'     => true,
                                                'isAjax'        => true,
                                                'multiple'      => true,
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-10 form-group">
                                        @include('admin::components.inputs.select', [
                                            'options'           => [
                                                'name'          => 'channels',
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.notification_template_crud.channels.label'),
                                                'placeholder'   => trans('admin::inputs.notification_template_crud.channels.placeholder'),
                                                'help'          => trans('admin::inputs.notification_template_crud.channels.help'),
                                                'subText'       => trans('admin::inputs.notification_crud.channels.subText'),
                                                'data'          => $notificationChannels,
                                                'text'          => fn($key, $value) => $value,
                                                'values'        => fn($key, $value) => $value,
                                                'clearable'     => true,
                                                'multiple'      => true,
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-10 form-group">
                                        @include('admin::components.inputs.select', [
                                            'options'           => [
                                                'name'          => 'priority',
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.notification_template_crud.priority.label'),
                                                'subText'       => trans('admin::inputs.notification_template_crud.priority.help'),
                                                'data'          => $notificationPriorities,
                                                'text'          => fn($key, $value) => $value,
                                                'values'        => fn($key, $value) => $key,
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-10 form-group">
                                    @include('admin::components.inputs.text', [
                                        'options'           => [
                                            'type'          => 'url',
                                            'name'          => 'link',
                                            'label'         => trans('admin::inputs.notification_crud.link.label'),
                                            'placeholder'   => trans('admin::inputs.notification_crud.link.placeholder'),
                                            'help'          => trans('admin::inputs.notification_crud.link.help'),
                                            'subText'       => trans('admin::inputs.notification_crud.link.subText'),
                                        ]
                                    ])
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-5"></div>

                                <div class="row">
                                    <div class="col-12">
                                        @include('admin::components.other.lang_crud', [
                                            'options'               => [
                                                'title'             => [
                                                    'show'          => true,
                                                    'required'      => true,
                                                    'value'         => null,
                                                ],
                                                'short_description' => [
                                                    'name'          => 'body',
                                                    'show'          => true,
                                                    'required'      => true,
                                                    'value'         => null,
                                                    'label'         => trans('admin::inputs.notification_template_crud.short_template.label'),
                                                    'placeholder'   => trans('admin::inputs.notification_template_crud.short_template.placeholder'),
                                                    'subText'       => trans('admin::inputs.notification_template_crud.short_template.subText'),
                                                ],
                                                'long_description'  => [
                                                    'name'          => 'long_template',
                                                    'show'          => true,
                                                    'required'      => false,
                                                    'value'         => null,
                                                    'label'         => trans('admin::inputs.notification_template_crud.long_template.label'),
                                                    'placeholder'   => trans('admin::inputs.notification_template_crud.long_template.placeholder'),
                                                    'subText'       => trans('admin::inputs.notification_template_crud.long_template.subText'),
                                                ],
                                            ]
                                        ])
                                    </div>
                                </div>

                            @endslot
                        @endcomponent
                    </div>
                </div>
            </div>

            <div class="col-lg-3"></div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function () {
            $('select[name="group"]').on('change', function () {
                let group = $(this).val();
                if (group === 'users') {
                    $('.user-list').show();
                    $('.admin-list').hide();
                    // Reset the admin list
                    $('#admins_id').val(null).trigger('change');
                } else {
                    $('.user-list').hide();
                    $('.admin-list').show();
                    // Reset the user list
                    $('#users_id').val(null).trigger('change');
                }

                // If group is changed, reset the channels
                resetChannels();
            });

            // Function to check if values are selected in user or admin lists
            function isUserOrAdminListSelected() {
                let userListSelected = $('#users_id').val() && $('#users_id').val().length > 0;
                let adminListSelected = $('#admins_id').val() && $('#admins_id').val().length > 0;

                return userListSelected || adminListSelected;
            }

            // Function to reset channels dropdown
            function resetChannels() {
                channelsDropdown.val([]).trigger('change'); // Reset the channels to empty selection
                // Re-enable all options in the Select2 dropdown
                channelsDropdown.find('option').each(function () {
                    $(this).prop('disabled', false); // Re-enable all options
                }).trigger('change.select2'); // Update Select2 view
            }

            // Event listener for user and admin select changes
            $('#users_id, #admins_id').on('change', function () {
                // If both user and admin lists are empty, reset the channels
                if (!isUserOrAdminListSelected()) {
                    resetChannels();
                }
            });

            // Ensure the channels dropdown is correctly selected and populated
            let channelsDropdown = $('select[name="channels[]"]');

            // Get FCM channels
            let fcmChannels = ['{{ NotificationChannels::FCM_MOBILE }}', '{{ NotificationChannels::FCM_WEB }}'];

            // Filter out FCM channels and get other channels
            let otherChannels = channelsDropdown.find('option').filter(function() {
                return !fcmChannels.includes($(this).val());
            });

            // Flag to avoid infinite loops
            let isProcessing = false;

            // Restrict the selection of channels
            channelsDropdown.on('change', function () {
                if (isProcessing) {
                    return; // Prevent re-triggering
                }

                // If there's a selected value in user or admin list, do not proceed with channel logic
                if (isUserOrAdminListSelected()) {
                    return;
                }

                isProcessing = true; // Start processing to avoid infinite loop

                let selectedChannels = $(this).val() || []; // Ensure we have an array

                let containsFcm     = selectedChannels.some(channel => fcmChannels.includes(channel));
                let containsNonFcm  = selectedChannels.some(channel => !fcmChannels.includes(channel));

                if (containsFcm && !containsNonFcm) {
                    // FCM channels selected, disable non-FCM channels
                    channelsDropdown.find('option').each(function () {
                        if (!fcmChannels.includes($(this).val())) {
                            $(this).prop('disabled', true); // Disable non-FCM channels
                        }
                    });

                    // Remove non-FCM channels from the selected values
                    selectedChannels = selectedChannels.filter(channel => fcmChannels.includes(channel));
                    $(this).val(selectedChannels).trigger('change'); // Update the value and Select2 view
                } else if (containsNonFcm && !containsFcm) {
                    // Non-FCM channels selected, disable FCM channels
                    channelsDropdown.find('option').each(function () {
                        if (fcmChannels.includes($(this).val())) {
                            $(this).prop('disabled', true); // Disable FCM channels
                        }
                    });

                    // Remove FCM channels from the selected values
                    selectedChannels = selectedChannels.filter(channel => !fcmChannels.includes(channel));
                    $(this).val(selectedChannels).trigger('change'); // Update the value and Select2 view
                } else {
                    // Enable all channels if no specific group is selected
                    channelsDropdown.find('option').prop('disabled', false); // Re-enable all options
                }

                // Refresh Select2 to reflect the changes in the view
                channelsDropdown.trigger('change.select2');

                isProcessing = false; // End processing
            });

            // When the user selects or deselects users
            $('#users_id, #admins_id').on('select2:select select2:unselect', function () {
                // Check if any user or admin is selected
                let hasUsersSelected = $('#users_id').val().length > 0 || $('#admins_id').val().length > 0;

                if (hasUsersSelected) {
                    // Enable all channels if users or admins are selected
                    channelsDropdown.find('option').prop('disabled', false).trigger('change');
                } else {
                    // If no users are selected, apply the FCM restriction logic
                    handleChannelSelection();
                }
            });

            // Function to handle channel selection and FCM restriction
            function handleChannelSelection() {
                let selectedChannels = channelsDropdown.val();

                if (selectedChannels && (selectedChannels.includes('FCM_MOBILE') || selectedChannels.includes('FCM_WEB'))) {
                    // Disable other channels if FCM channels are selected
                    channelsDropdown.find('option').each(function () {
                        if (!fcmChannels.includes($(this).val())) {
                            $(this).prop('disabled', true);
                        }
                    }).trigger('change');
                } else {
                    // Re-enable all channels if no FCM channels are selected
                    channelsDropdown.find('option').prop('disabled', false).trigger('change');
                }
            }

            // Run the logic initially in case there are pre-selected values
            handleChannelSelection();
        });
    </script>
@endpush
