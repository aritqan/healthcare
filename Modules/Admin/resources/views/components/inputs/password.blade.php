@isset($options)
    @php
        $VALUE = array_merge([
            'id'                    => null,
            'name'                  => null,
            'placeholder'           => null,
            'help'                  => null,
            'subText'               => null,
            'class'                 => null,
            'label'                 => null,
            'solid'                 => true,
            'required'              => false,
            'readonly'              => false,
            'disabled'              => false,
            'inputmode'             => 'text',
            'visibleToggle'         => true,
            'highlight'             => true,
            'view'                  => 'DEFAULT', // DEFAULT | INLINE
            'input_size'            => 'col-lg-8',
            'label_size'            => 'col-lg-12',
        ], $options);

        $VALUE['id']    = !empty($VALUE['id']) ? $VALUE['id'] : $VALUE['name'];

    @endphp

    @if($VALUE['view'] == 'DEFAULT')
        <div class="mb-1 fv-row" data-kt-password-meter="true">
            @include('admin::components.input_particles.label'   , ['VALUE' => $VALUE])
            @include('admin::components.input_particles.password', ['VALUE' => $VALUE])
        </div>
    @elseif($VALUE['view'] == 'INLINE')
        <div class="row" data-kt-password-meter="true">
            @include('admin::components.input_particles.label', ['VALUE' => $VALUE])
            <div class="form-group {{ $VALUE['input_size'] }}">
                @include('admin::components.input_particles.password', ['VALUE' => $VALUE])
                @if (!empty($VALUE['subText']))
                    <div class="form-text">
                        {{$VALUE['subText']}}
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if ($VALUE['view'] == 'DEFAULT' && !empty($VALUE['subText']))
        <span class="form-text text-muted">{{$VALUE['subText']}}</span>
    @endif
@endisset
