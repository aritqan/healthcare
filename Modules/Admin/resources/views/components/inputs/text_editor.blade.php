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
            'value'                 => null,
            'disabled'              => false,
            'height'                => '500px',
            'view'                  => 'DEFAULT', // DEFAULT | INLINE
            'input_size'            => 'col-lg-8',
            'label_size'            => 'col-lg-12',
        ], $options);

        $VALUE['id']    = $VALUE['id'] ?? $VALUE['name'];
        $VALUE['id']    = str_replace('[', '_', $VALUE['id']);
        $VALUE['id']    = str_replace(']', '', $VALUE['id']);
    @endphp

    @if($VALUE['view'] == 'DEFAULT')
        @include('admin::components.input_particles.label', ['VALUE' => $VALUE])
        @include('admin::components.input_particles.text_editor' , ['VALUE' => $VALUE])
    @elseif($VALUE['view'] == 'INLINE')
        <div class="row">
            @include('admin::components.input_particles.label', ['VALUE' => $VALUE])
            <div class="form-group {{ $VALUE['input_size'] }}">
                @include('admin::components.input_particles.text_editor' , ['VALUE' => $VALUE])
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
