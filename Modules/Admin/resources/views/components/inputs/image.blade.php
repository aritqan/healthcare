@isset($options)
    @php
        $VALUE = array_merge([
            'id'            => null,
            'name'          => null,
            'label'         => null,
            'help'          => null,
            'subText'       => null,
            'class'         => null,
            'required'      => false,
            'value'         => null,
            'readonly'      => false,
            'disabled'      => false,
            'width'         => '100',
            'height'        => '100',
            'isAvatar'      => false,
            'default'       => null,
            'circle'        => false,
            'accept'        => 'image/*',
            'uploadText'    => trans('admin::strings.upload_image'),
            'cancelText'    => trans('admin::strings.cancel_image'),
            'removeText'    => trans('admin::strings.remove_image'),
            'canRemove'     => true,
            'view'          => 'DEFAULT', // DEFAULT | INLINE
            'input_size'    => 'col-lg-8',
            'label_size'    => 'col-lg-12',
        ], $options);

        $VALUE['id']      = !empty($VALUE['id']) ? $VALUE['id'] : $VALUE['name'];
        $VALUE['default'] = is_null($VALUE['default']) ? ($VALUE['isAvatar'] ? config('admin.avatar.default') : asset('modules/admin/metronic/demo/media/svg/files/blank-image.svg')) : $VALUE['default'];
        $VALUE['width']   = $VALUE['width']  . 'px';
        $VALUE['height']  = $VALUE['height'] . 'px';
    @endphp

    @if($VALUE['view'] == 'DEFAULT')
        @include('admin::components.input_particles.label', ['VALUE' => $VALUE])
        @include('admin::components.input_particles.image', ['VALUE' => $VALUE])
    @elseif($VALUE['view'] == 'INLINE')
        <div class="row">
            @include('admin::components.input_particles.label', ['VALUE' => $VALUE])
            <div class="form-group {{ $VALUE['input_size'] }}">
                @include('admin::components.input_particles.image', ['VALUE' => $VALUE])
                @if (!empty($VALUE['subText']))
                    <div class="form-text">
                        {{$VALUE['subText']}}
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if ($VALUE['view'] == 'DEFAULT' && !empty($VALUE['subText']))
        <div class="form-text">
            {{$VALUE['subText']}}
        </div>
    @endif
@endisset
