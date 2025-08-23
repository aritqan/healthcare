@isset($options)
    @php
        $VALUE = array_merge([
            'tabContentId'      => 'myTabContent',
            'tabId'             => 'tab_',
            'langTabId'         => 'kt_tab_pane_',
            'title'             => [
                'show'          => false,
                'required'      => false,
                'value'         => function($model, $locale) {return null;},
            ],
            'name'              => [
                'show'          => false,
                'required'      => false,
                'value'         => function($model, $locale) {return null;},
            ],
            'description'       => [
                'show'          => false,
                'required'      => false,
                'value'         => function($model, $locale) {return null;}
            ],
            'long_description'  => [
                'show'          => false,
                'required'      => false,
                'value'         => function($model, $locale) {return null;}
            ],
            'short_description' => [
                'show'          => false,
                'required'      => false,
                'value'         => function($model, $locale) {return null;}
            ],
            'image'             => [
                'show'          => false,
                'required'      => false,
                'width'         => '100',
                'height'        => '100',
                'accept'        => 'image/*',
                'canRemove'     => true,
                'value'         => function($model, $locale) {return null;}
            ],
        ], $options);

        $titleInputName             = $VALUE['title']['name']               ?? 'title';
        $nameInputName              = $VALUE['name']['name']                ?? 'name';
        $descriptionInputName       = $VALUE['description']['name']         ?? 'description';
        $longDescriptionInputName   = $VALUE['long_description']['name']    ?? 'long_description';
        $shortDescriptionInputName  = $VALUE['short_description']['name']   ?? 'short_description';
        $imageInputName             = $VALUE['image']['name']               ?? 'image';
    @endphp

    @include('admin::particles.languages.tab', [
        'options'   => [
            'tabId' => $VALUE['tabId'],
            'href'  => '#' . $VALUE['langTabId'],
        ]
    ])

    <div class="tab-content" id="{{ $VALUE['tabContentId'] }}">
        @foreach ($_ALL_LOCALE_ as $locale => $lang)

            <div @class(['tab-pane fade'  => true, 'active show' => $locale == $_LOCALE_]) id="{{$VALUE['langTabId'] . $locale}}" role="tabpanel">

                @if($VALUE['image']['show'])
                    <div class="row">
                        <div class="col-12 mb-5 form-group">
                            @include('admin::components.inputs.image', [
                                'options'       => [
                                    'name'      => $imageInputName . '[' . $locale . ']',
                                    'width'     => $VALUE['image']['width'] ?? '100',
                                    'height'    => $VALUE['image']['height'] ?? '100',
                                    'canRemove' => $VALUE['image']['canRemove'] ?? true,
                                    'required'  => $VALUE['image']['required'],
                                    'label'     => $VALUE['image']['label'] ?? trans('admin::inputs.base_crud.image_lang.label', ['locale' => $locale]),
                                    'subText'   => $VALUE['image']['subText'] ?? trans('admin::inputs.contents_crud.image.subText'),
                                    'help'      => $VALUE['image']['help'] ?? trans('admin::inputs.contents_crud.image.help'),
                                    'accept'    => $VALUE['image']['accept'] ?? 'image/*',
                                    'value'     => isset($model) ? $VALUE['image']['value']($model, $locale) : $VALUE['image']['value'],
                                ]
                            ])
                        </div>
                    </div>
                @endif

                @if ($VALUE['title']['show'])
                    <div class="row">
                        <div class="col-12 mb-5 form-group">
                            @include('admin::components.inputs.text', [
                                'options'           => [
                                    'name'          => $titleInputName . '[' . $locale . ']',
                                    'required'      => $VALUE['title']['required'],
                                    'label'         => $VALUE['title']['label'] ?? trans('admin::inputs.base_crud.title.label', ['locale' => $locale]),
                                    'placeholder'   => $VALUE['title']['placeholder'] ?? trans('admin::inputs.base_crud.title.placeholder'),
                                    'subText'       => $VALUE['title']['subText'] ?? trans('admin::inputs.base_crud.title.help'),
                                    'value'         => isset($model) ? $VALUE['title']['value']($model, $locale) : $VALUE['title']['value']
                                ]
                            ])
                        </div>
                    </div>
                @endif

                @if ($VALUE['name']['show'])
                    <div class="row">
                        <div class="col-12 mb-5 form-group">
                            @include('admin::components.inputs.text', [
                                'options'           => [
                                    'name'          => $nameInputName . '[' . $locale . ']',
                                    'required'      => $VALUE['name']['required'],
                                    'label'         => $VALUE['name']['label'] ?? trans('admin::inputs.base_crud.name.label', ['locale' => $locale]),
                                    'placeholder'   => $VALUE['name']['placeholder'] ?? trans('admin::inputs.base_crud.name.placeholder'),
                                    'subText'       => $VALUE['name']['subText'] ?? trans('admin::inputs.base_crud.name.help'),
                                    'value'         => isset($model) ? $VALUE['name']['value']($model, $locale) : $VALUE['name']['value']
                                ]
                            ])
                        </div>
                    </div>
                @endif

                @if ($VALUE['description']['show'])
                    <div class="row">
                        <div class="col-12 mb-5 form-group">
                            @include('admin::components.inputs.textarea', [
                                'options'           => [
                                    'name'          => $descriptionInputName . '[' . $locale . ']',
                                    'required'      => $VALUE['description']['required'],
                                    'label'         => $VALUE['description']['label'] ?? trans('admin::inputs.base_crud.description.label', ['locale' => $locale]),
                                    'placeholder'   => $VALUE['description']['placeholder'] ?? trans('admin::inputs.base_crud.description.placeholder'),
                                    'subText'       => $VALUE['description']['subText'] ?? trans('admin::inputs.base_crud.description.help'),
                                    'value'         => isset($model) ?  $VALUE['description']['value']($model, $locale) : $VALUE['description']['value'],
                                ]
                            ])
                        </div>
                    </div>
                @endif

                @if ($VALUE['short_description']['show'])
                    <div class="row">
                        <div class="col-12 mb-5 form-group">
                            @include('admin::components.inputs.textarea', [
                                'options'           => [
                                    'name'          => $shortDescriptionInputName . '[' . $locale . ']',
                                    'required'      => $VALUE['short_description']['required'],
                                    'label'         => $VALUE['short_description']['label'] ?? trans('admin::inputs.base_crud.short_description.label', ['locale' => $locale]),
                                    'placeholder'   => $VALUE['short_description']['placeholder'] ?? trans('admin::inputs.base_crud.short_description.placeholder'),
                                    'subText'       => $VALUE['short_description']['subText'] ?? trans('admin::inputs.base_crud.short_description.help'),
                                    'value'         => isset($model) ?  $VALUE['short_description']['value']($model, $locale) : $VALUE['short_description']['value'],
                                ]
                            ])
                        </div>
                    </div>
                @endif

                @if ($VALUE['long_description']['show'])
                    <div class="row">
                        <div class="col-12 mb-5 form-group">
                            @include('admin::components.inputs.text_editor', [
                                'options'           => [
                                    'name'          => $longDescriptionInputName . '[' . $locale . ']',
                                    'required'      => $VALUE['long_description']['required'],
                                    'label'         => $VALUE['long_description']['label'] ?? trans('admin::inputs.base_crud.long_description.label', ['locale' => $locale]),
                                    'placeholder'   => $VALUE['long_description']['placeholder'] ?? trans('admin::inputs.base_crud.long_description.placeholder'),
                                    'subText'       => $VALUE['long_description']['subText'] ?? trans('admin::inputs.base_crud.long_description.help'),
                                    'value'         => isset($model) ? $VALUE['long_description']['value']($model, $locale) : $VALUE['long_description']['value'],
                                ]
                            ])
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endisset
