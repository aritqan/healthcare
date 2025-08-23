@isset($options)
    @php
        $VALUE = array_merge([
            'id'                    => null,
            'name'                  => null,
            'fullNumberName'        => 'phone_number',
            'help'                  => null,
            'subText'               => null,
            'class'                 => null,
            'label'                 => null,
            'solid'                 => true,
            'required'              => false,
            'additional_info'       => [],
            'value'                 => null,
            'data'                  => [],
            'readonly'              => false,
            'disabled'              => false,
            'inputmode'             => 'tel',
            'view'                  => 'DEFAULT', // DEFAULT | INLINE
            'input_size'            => 'col-lg-8',
            'label_size'            => 'col-lg-12',
        ], $options);

        $VALUE['id']    = !empty($VALUE['id']) ? $VALUE['id'] : $VALUE['name'];
        $VALUE['value'] = old($VALUE['name'], $VALUE['value']);
    @endphp

    @if($VALUE['view'] == 'DEFAULT')
        @include('admin::components.input_particles.label', ['VALUE' => $VALUE])
        @include('admin::components.input_particles.phone', ['VALUE' => $VALUE])

    @elseif($VALUE['view'] == 'INLINE')
        <div class="row">
            @include('admin::components.input_particles.label', ['VALUE' => $VALUE])
            <div class="form-group {{ $VALUE['input_size'] }}">
                @include('admin::components.input_particles.phone', ['VALUE' => $VALUE])
                @if (!empty($VALUE['subText']))
                    <div class="form-text">
                        {{$VALUE['subText']}}
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if (!empty($VALUE['subText']))
        <span class="form-text text-muted">{{$VALUE['subText']}}</span>
    @endif
@endisset
