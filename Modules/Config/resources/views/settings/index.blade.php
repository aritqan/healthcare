@php
    use Modules\Config\Enums\SettingGroups;
@endphp

@extends('admin::layouts.master', ['title' => trans('admin::cruds.settings.edit')])

@section('toolbar')
    @include('admin::includes.toolbar', [
        'options'               => [
            'title'             => trans('admin::dashboard.aside_menu.setting_management.settings'),
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
            <div class="col-lg-2"></div>

            <div class="col-xxl-8 col-12">
                <div class="card card-bordered mb-5">
                    <div class="card-header">
                        <h3 class="card-title">
                            @lang('admin::cruds.settings.title')
                        </h3>
                    </div>
                    <div class="card-body">
                        @component('admin::components.forms.form', [
                                'options'                   => [
                                    'isAjax'                => true,
                                    'method'                => 'PUT',
                                    'addEmptyCheckbox'      => true,
                                    'action'                => route('config.settings.postUpdate'),
                                ]
                            ])
                            @slot('fields')
                                <div class="row">
                                    <div class="col-12 mb-10 form-group">

                                        <ul class="nav nav-tabs flex-nowrap text-nowrap">
                                            @foreach ($settings as $groupName => $group)
                                                @php
                                                    $active = $loop->first ? 'active' : '';
                                                @endphp

                                                @if($groupName == SettingGroups::DEVELOPERS && ! app('owner'))
                                                    @continue
                                                @endif

                                                <li class="nav-item">
                                                    @component('admin::components.other.hyperlink', [
                                                            'options'                   => [
                                                                'id'                    => 'tab_' . $groupName,
                                                                'href'                  => '#kt_tab_pane_' . $groupName,
                                                                'class'                 => 'nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0 ' . $active,
                                                                'attributes'            => [
                                                                    'data-bs-toggle'    => 'tab',
                                                                    'role'              => 'tab',
                                                                    'aria-selected'     => 'true',
                                                                ]
                                                            ]
                                                        ])
                                                        <span >
                                                            @lang('config::settings.groups.' . $groupName . '.title')
                                                        </span>
                                                    @endcomponent
                                                </li>

                                            @endforeach
                                        </ul>

                                        <div class="tab-content mt-5" id="settingTabContent">
                                            @foreach ($settings as $groupName => $groups)
                                                @php
                                                    $active = $loop->first ? 'active show' : '';
                                                @endphp

                                                @if($groupName == SettingGroups::DEVELOPERS && ! app('owner'))
                                                    @continue
                                                @endif

                                                <div @class(['tab-pane fade'  => true, 'active show' => $active]) id="kt_tab_pane_{{$groupName}}" role="tabpanel">
                                                    <div class="row">
                                                        @foreach ($groups as $setting)
                                                            @if($setting->translatable)
                                                                @php
                                                                    $model = $setting;
                                                                @endphp
                                                                @include('admin::components.other.lang_crud', [
                                                                    'options'           => [
                                                                        'tabId'         => 'tab_' . $setting->id,
                                                                        'langTabId'     => 'kt_tab_pane_' . $setting->id,
                                                                        'title'         => [
                                                                            'show'      => $setting->type == 'text' || $setting->type == 'url',
                                                                            'name'      => $setting->key,
                                                                            'label'     => $setting->smartTrans('title'),
                                                                            'placeholder'=> $setting->smartTrans('title'),
                                                                            'subText'   => !empty($setting->smartTrans('description')) ? $setting->smartTrans('description') : $setting->smartTrans('title'),
                                                                            'required'  => $setting->is_required,
                                                                            'value'     => function($model, $locale) {
                                                                                return $model->smartTrans('trans_value', $locale, true);
                                                                            },
                                                                        ],
                                                                        'description'   => [
                                                                            'show'      => $setting->type == 'textarea',
                                                                            'name'      => $setting->key,
                                                                            'label'     => $setting->smartTrans('title'),
                                                                            'placeholder'=> $setting->smartTrans('title'),
                                                                            'subText'   => !empty($setting->smartTrans('description')) ? $setting->smartTrans('description') : $setting->smartTrans('title'),
                                                                            'required'  => $setting->is_required,
                                                                            'value'     => function($model, $locale) {
                                                                                return $model->smartTrans('trans_value', $locale, true);
                                                                            },
                                                                        ],
                                                                    ]
                                                                ])
                                                            @else
                                                                <div class="col-12 mb-5 form-group">
                                                                    @if($setting->type == 'text' || $setting->type == 'url' || $setting->type == 'number')
                                                                        @include('admin::components.inputs.text', [
                                                                            'options'           => [
                                                                                'type'          => $setting->type,
                                                                                'name'          => $setting->key,
                                                                                'required'      => $setting->is_required,
                                                                                'label'         => $setting->smartTrans('title'),
                                                                                'placeholder'   => $setting->smartTrans('title'),
                                                                                'subText'       => $setting->smartTrans('description'),
                                                                                'value'         => $setting->value
                                                                            ]
                                                                        ])
                                                                    @elseif($setting->type == 'textarea')
                                                                        @include('admin::components.inputs.textarea', [
                                                                            'options'           => [
                                                                                'name'          => $setting->key,
                                                                                'required'      => $setting->is_required,
                                                                                'label'         => $setting->smartTrans('title'),
                                                                                'placeholder'   => $setting->smartTrans('title'),
                                                                                'subText'       => $setting->smartTrans('description'),
                                                                                'value'         => $setting->value
                                                                            ]
                                                                        ])
                                                                    @elseif ($setting->type == 'image')
                                                                        @include('admin::components.inputs.image', [
                                                                            'options'           => [
                                                                                'view'          => 'INLINE',
                                                                                'name'          => $setting->key,
                                                                                'required'      => $setting->is_required,
                                                                                'label'         => $setting->smartTrans('title'),
                                                                                'subText'       => $setting->smartTrans('description'),
                                                                                'default'       => empty($setting->value) ? $setting->media_url : null,
                                                                                'value'         => !empty($setting->value) ? $setting->media_url : null,
                                                                            ]
                                                                        ])
                                                                    @elseif ($setting->type == 'button')
                                                                        @include('admin::components.other.hyperlink', [
                                                                            'options'               => [
                                                                                'id'                => 'button_' . $setting->id,
                                                                                'title'             => $setting->smartTrans('title'),
                                                                                'href'              => $setting->action_url,
                                                                                'class'             => 'btn btn-danger w-25',
                                                                                'isAjax'            => true,
                                                                                'method'            => 'POST',
                                                                                'withConfirmDialog' => true,
                                                                            ]
                                                                        ])
                                                                    @elseif($setting->type == 'switch')
                                                                       @include('admin::components.inputs.switch', [
                                                                           'options'          => [
                                                                               'id'           => 'settings_' . $setting->id,
                                                                               'name'         => $setting->key,
                                                                               'label'        => $setting->smartTrans('title'),
                                                                               'checked'      => (bool) $setting->value,
                                                                           ]
                                                                       ])
                                                                    @elseif($setting->type == 'phone')
                                                                        @include('admin::components.inputs.phone', [
                                                                            'options'           => [
                                                                                'id'            => 'settings_' . $setting->id,
                                                                                'name'          => $setting->key,
                                                                                'required'      => $setting->is_required,
                                                                                'label'         => $setting->smartTrans('title'),
                                                                                'subText'       => $setting->smartTrans('description'),
                                                                                'value'         => $setting->value
                                                                            ]
                                                                        ])
                                                                    @elseif($setting->type == 'select')
                                                                        @include('admin::components.inputs.select', [
                                                                            'options'           => [
                                                                                'id'            => 'settings_' . $setting->id,
                                                                                'name'          => $setting->key,
                                                                                'class'         => 'language-select',
                                                                                'required'      => $setting->is_required,
                                                                                'label'         => $setting->smartTrans('title'),
                                                                                'help'          => $setting->smartTrans('description'),
                                                                                'value'         => $setting->value,
                                                                                'data'          => $setting->options,
                                                                                'text'          => function($key, $value) {return $value['native'];},
                                                                                'values'        => function($key, $value) {return $key;},
                                                                                'select'        => function($key, $value, $selected) {return $key == $selected;},
                                                                            ]
                                                                        ])
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-5"></div>
                            @endslot
                        @endcomponent
                    </div>
                </div>
            </div>

            <div class="col-lg-2"></div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function () {
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                $('[data-toggle="switchbutton"]').parent().css({
                    'width': '82px',
                    'height': '42px',
                });
            });
        });
    </script>
@endpush
