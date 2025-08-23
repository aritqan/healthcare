@isset($options)
    @php
        $VALUE = array_merge([
            'id'                    => null,
            'name'                  => null,
            'type'                  => 'text',
            'placeholder'           => null,
            'help'                  => null,
            'subText'               => null,
            'class'                 => null,
            'label'                 => null,
            'onlyDigit'             => false,
            'isPhone'               => false,
            'solid'                 => true,
            'required'              => false,
            'additional_info'       => [],
            'icon'                  => '',
            'value'                 => null,
            'data'                  => [],
            'readonly'              => false,
            'disabled'              => false,
            'maxlength'             => null,
            'step'                  => 'any',
            'inputmode'             => 'text',
            'min'                   => null,
            'max'                   => null,
            'mask'                  => null,
            'regex'                 => null,
            'emailMask'             => false,
            'view'                  => 'DEFAULT', // DEFAULT | INLINE
            'input_size'            => 'col-lg-8',
            'label_size'            => 'col-lg-12',
        ], $options);

        $VALUE['id']    = !empty($VALUE['id']) ? $VALUE['id'] : $VALUE['name'];
    @endphp

    @if($VALUE['view'] == 'DEFAULT')
        @include('admin::components.input_particles.label', ['VALUE' => $VALUE])
        @include('admin::components.input_particles.text' , ['VALUE' => $VALUE])
    @elseif($VALUE['view'] == 'INLINE')
        <div class="row">
            @include('admin::components.input_particles.label', ['VALUE' => $VALUE])
            <div class="form-group {{ $VALUE['input_size'] }}">
                @include('admin::components.input_particles.text' , ['VALUE' => $VALUE])
                <div class="form-text">
                    {{$VALUE['subText']}}
                </div>
            </div>
        </div>
    @endif

    @if ($VALUE['view'] == 'DEFAULT' && !empty($VALUE['subText']))
        <span class="form-text text-muted">{{$VALUE['subText']}}</span>
    @endif
@endisset
