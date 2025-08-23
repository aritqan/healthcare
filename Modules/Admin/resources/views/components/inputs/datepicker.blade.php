@isset($options)
    @php
        $VALUE = array_merge([
            'id'                    => null,
            'name'                  => null,
            'inputmode'             => 'datetime-local',
            'label'                 => null,
            'placeholder'           => null,
            'help'                  => null,
            'subText'               => null,
            'class'                 => null,
            'solid'                 => true,
            'required'              => false,
            'value'                 => null,
            'readonly'              => false,
            'disabled'              => false,
            'mode'                  => 'single', // single | multiple | range
            'withTime'              => false,
            'dateFormat'            => 'Y-m-d',
            'view'                  => 'DEFAULT',
            'input_size'            => 'col-lg-8',
            'label_size'            => 'col-lg-12',
        ], $options);

        $VALUE['id']            = !empty($VALUE['id']) ? $VALUE['id'] : $VALUE['name'];
        $VALUE['dateFormat']    = $VALUE['withTime'] ? 'Y-m-d H:i' : $VALUE['dateFormat'];

    @endphp

    @if($VALUE['view'] == 'DEFAULT')
        @include('admin::components.input_particles.label', ['VALUE' => $VALUE])
        @include('admin::components.input_particles.datepicker' , ['VALUE' => $VALUE])
    @elseif($VALUE['view'] == 'INLINE')
        <div class="row">
            @include('admin::components.input_particles.label', ['VALUE' => $VALUE])
            <div class="form-group {{ $VALUE['input_size'] }}">
                @include('admin::components.input_particles.datepicker' , ['VALUE' => $VALUE])
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
