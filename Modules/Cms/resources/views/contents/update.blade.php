@php
    use Modules\Cms\Models\Content;
    use Modules\Cms\Models\ContentTag;
@endphp

@extends('admin::layouts.master', ['title' => trans('admin::cruds.'.$type.'.edit')])

@section('toolbar')
    @include('admin::includes.toolbar', [
        'options'               => [
            'title'             => Content::getTypeTitle($type),
            'backUrl'           => route('cms.contents.index', ['type' => $type]),
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
                            @lang('admin::cruds.'.$type.'.edit')
                        </h3>
                    </div>
                    <div class="card-body">
                        @component('admin::components.forms.form', [
                                'options'       => [
                                    'isAjax'    => true,
                                    'action'    => route('cms.contents.postUpdate', [$model->id, 'type' => $type]),
                                    'method'    => 'PUT',
                                ]
                            ])
                            @slot('fields')

                                @include('admin::components.inputs.text', [
                                    'options'   => [
                                        'type'  => 'hidden',
                                        'name'  => 'type',
                                        'value' => $type,
                                    ]
                                ])

                                @if(Content::typeHasField($type, 'slug'))
                                    <div class="row">
                                        <div class="col-12 mb-10 form-group">
                                            @include('admin::components.inputs.text', [
                                                'options'           => [
                                                    'name'          => 'slug',
                                                    'readonly'      => true,
                                                    'label'         => trans('admin::inputs.contents_crud.slug.label'),
                                                    'placeholder'   => trans('admin::inputs.contents_crud.slug.placeholder'),
                                                    'help'          => trans('admin::inputs.contents_crud.slug.help'),
                                                    'subText'       => trans('admin::inputs.contents_crud.slug.subText'),
                                                    'required'      => true,
                                                    'class'         => 'to-lower space-underscore-to-dash only-english-letters-and-numbers',
                                                    'value'         => $model->slug,
                                                ]
                                            ])
                                        </div>
                                    </div>
                                @endif

                                @if(Content::typeHasSelectField($type))
                                    @foreach (Content::getSelectField($type) as $key => $content)
                                        @php
                                            $defaultOptions = [
                                                'name'          => $key,
                                                'label'         => trans('admin::inputs.contents_crud.'.$type.'.'.$key.'.label'),
                                                'placeholder'   => trans('admin::inputs.contents_crud.'.$type.'.'.$key.'.placeholder'),
                                                'help'          => trans('admin::inputs.contents_crud.'.$type.'.'.$key.'.help'),
                                                'required'      => $content['required'] ?? false,
                                                'multiple'      => $content['multiple'] ?? false,
                                                'isAjax'        => $content['isAjax'] ?? false,
                                            ];
                                            $selectOptions = [];
                                            if(! $content['isAjax']) {
                                                $selectOptions['data']      = Content::getSelectData($type, $key);
                                                $selectOptions['text']      = fn($selectKey, $value) => trans($value);
                                                $selectOptions['values']    = fn($selectKey, $value) => $selectKey;
                                                $selectOptions['value']     = $model->custom_properties[$key] ?? null;
                                                $selectOptions['select']    = function($selectKey, $value) use ($model, $key) {
                                                    return $model->custom_properties[$key] ?? null == $selectKey;
                                                };
                                            } else {
                                                $selectOptions['url']       = route($content['data']);
                                                $selectOptions['clearable'] = $content['clearable'];
                                                $selectOptions['withImg']   = $content['withImg'] ?? false;
                                                $tags = [];
                                                switch ($key) {
                                                    case 'tags':
                                                        $tags = $model->tags;
                                                        break;
                                                }
                                                $handledTags = [];
                                                foreach ($tags as $tag) {
                                                    $handledTags[] = $tag->formAjaxArray();
                                                }
                                                $selectOptions['selected'] = $handledTags ?? [];
                                            }

                                            $options = array_merge($defaultOptions, $selectOptions);
                                        @endphp
                                        <div class="row">
                                            <div class="col-12 mb-10 form-group">
                                            @include('admin::components.inputs.select', [
                                                'options'  => $options
                                            ])
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                @if(Content::typeHasField($type, 'link'))
                                    <div class="row">
                                        <div class="col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'type'          => 'url',
                                                'name'          => 'link',
                                                'required'      => Content::isFieldRequired($type, 'link'),
                                                'label'         => trans('admin::inputs.contents_crud.sliders.link.label'),
                                                'placeholder'   => trans('admin::inputs.contents_crud.sliders.link.placeholder'),
                                                'help'          => trans('admin::inputs.contents_crud.sliders.link.help'),
                                                'value'         => $model->link,
                                            ]
                                        ])
                                        </div>
                                    </div>
                                @endif

                                @if(Content::typeHasField($type, 'published_at'))
                                    <div class="row">
                                        <div class="col-12 mb-10 form-group">
                                            @include('admin::components.inputs.datepicker', [
                                                'options'           => [
                                                    'name'          => 'published_at',
                                                    'required'      => Content::isFieldRequired($type, 'published_at'),
                                                    'label'         => trans('admin::inputs.contents_crud.published_at.label'),
                                                    'placeholder'   => trans('admin::inputs.contents_crud.published_at.placeholder'),
                                                    'help'          => trans('admin::inputs.contents_crud.published_at.help'),
                                                    'value'         => $model->published_at,
                                                ]
                                            ])
                                        </div>
                                    </div>
                                @endif

                                @if(Content::typeHasField($type, 'can_be_deleted'))
                                    <div class="row">
                                        <div class="col-12 mb-10 form-group">
                                            @include('admin::components.inputs.select', [
                                                'options'           => [
                                                    'name'          => 'can_be_deleted',
                                                    'required'      => Content::isFieldRequired($type, 'can_be_deleted'),
                                                    'label'         => trans('admin::inputs.content_category_crud.can_be_deleted.label'),
                                                    'help'          => trans('admin::inputs.content_category_crud.can_be_deleted.help'),
                                                    'data'          => YES_NO_DATA,
                                                    'text'          => function($key, $value) {return trans('base::base.yes_no.' . $value['text']);},
                                                    'values'        => function($key, $value) {return $value['value'];},
                                                    'value'         => $model->can_be_deleted,
                                                    'select'        => function($key, $value) use ($model) {
                                                        return $model->can_be_deleted == $value['value'];
                                                    },
                                                ]
                                            ])
                                        </div>
                                    </div>
                                @endif

                                <div class="separator separator-dashed my-5"></div>

                                <div class="row">
                                    <div class="col-12">
                                        @include('admin::components.other.lang_crud', [
                                            'options'               => [
                                                'title'             => [
                                                    'show'          => Content::typeHasField($type, 'title'),
                                                    'required'      => Content::isFieldRequired($type, 'title'),
                                                    'value'         => function($model, $locale) {
                                                        return $model->smartTrans('title', $locale, true);
                                                    },

                                                ],
                                                'description'       => [
                                                    'show'          => Content::typeHasField($type, 'description'),
                                                    'required'      => Content::isFieldRequired($type, 'description'),
                                                    'name'          => 'short_description',
                                                    'value'         => function($model, $locale) {
                                                        return $model->smartTrans('short_description', $locale, true);
                                                    },
                                                ],
                                                'long_description'  => [
                                                    'show'          => Content::typeHasField($type, 'long_description'),
                                                    'required'      => Content::isFieldRequired($type, 'long_description'),
                                                    'name'          => 'long_description',
                                                    'value'         => function($model, $locale) {
                                                        return $model->smartTrans('long_description', $locale, true);
                                                    },
                                                ],
                                                'image'             => [
                                                    'show'          => Content::typeHasField($type, 'image'),
                                                    'required'      => Content::isFieldRequired($type, 'image'),
                                                    'canRemove'     => false,
                                                    'help'          => trans('admin::inputs.contents_crud.image.help', [
                                                        'dimentions'=> Content::getImageDimensions($type)->last(),
                                                        'types'     => Content::getImageTypes($type),
                                                    ]),
                                                    'subText'       => trans('admin::inputs.contents_crud.image.subText', [
                                                        'dimentions'=> Content::getImageDimensions($type)->last(),
                                                        'types'     => Content::getImageTypes($type),
                                                    ]),
                                                    'width'         => Content::getImagePreviewDimension($type)[0] ?? '100',
                                                    'height'        => Content::getImagePreviewDimension($type)[1] ?? '100',
                                                    'accept'        => getImageTypes(false, Content::$imageTypes),
                                                    'value'         => function($model, $locale) {
                                                        return $model->transImageUrl('content', $locale, '', true);
                                                    },
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
