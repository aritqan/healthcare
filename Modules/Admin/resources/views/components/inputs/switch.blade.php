@isset($options)
    @php
        $VALUE = array_merge([
            'id'                    => null,
            'name'                  => null,
            'label'                 => null,
            'class'                 => null,
            'color'                 => 'primary',
            'required'              => false,
            'checked'               => false,
            'disabled'              => false,
            'size'                  => 'md',
            'onColor'               => 'success',
            'offColor'              => 'danger',
            'onLabel'               => trans('base::base.switch.on'),
            'offLabel'              => trans('base::base.switch.off'),
            'view'                  => 'DEFAULT', // DEFAULT | INLINE
            'input_size'            => 'col-lg-8',
            'label_size'            => 'col-lg-12',
            'value'                 => null,
        ], $options);

        $VALUE['id'] = !empty($VALUE['id']) ? $VALUE['id'] : $VALUE['name'];
    @endphp

    @if($VALUE['view'] == 'DEFAULT')
        @include('admin::components.input_particles.label'  , ['VALUE' => $VALUE])
        @include('admin::components.input_particles.switch' , ['VALUE' => $VALUE])
    @elseif($VALUE['view'] == 'INLINE')
        <div class="row">
            @include('admin::components.input_particles.label', ['VALUE' => $VALUE])
            <div class="form-group {{ $VALUE['input_size'] }}">
                @include('admin::components.input_particles.switch' , ['VALUE' => $VALUE])
            </div>
        </div>
    @endif
@endisset
