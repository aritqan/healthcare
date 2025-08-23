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
            'icon'                  => '',
            'value'                 => null,
            'readonly'              => false,
            'disabled'              => false,
            'rows'                  => 5,
            'cols'                  => 3,
            'maxlength'             => config('base.input_size.textarea.max', '1000'),
            'view'                  => 'DEFAULT', // DEFAULT | INLINE
            'input_size'            => 'col-lg-8',
            'label_size'            => 'col-lg-12',
        ], $options);

        $VALUE['id']    = !empty($VALUE['id']) ? $VALUE['id'] : $VALUE['name'];
        // $VALUE['value'] = old($VALUE['name'], $VALUE['value']);
    @endphp

    @if($VALUE['view'] == 'DEFAULT')
        @include('admin::components.input_particles.label', ['VALUE' => $VALUE])
        @include('admin::components.input_particles.textarea' , ['VALUE' => $VALUE])
    @elseif($VALUE['view'] == 'INLINE')
        <div class="row">
            @include('admin::components.input_particles.label', ['VALUE' => $VALUE])
            <div class="form-group {{ $VALUE['input_size'] }}">
                @include('admin::components.input_particles.textarea' , ['VALUE' => $VALUE])
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
