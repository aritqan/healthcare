@isset($options)
    @php
        $VALUE = array_merge([
            'id'                    => null,
            'name'                  => 'select',
            'label'                 => null,
            'help'                  => null,
            'subText'               => null,
            'select2'               => true,
            'isAjax'                => false,
            'url'                   => route('base.empty_data'),
            'class'                 => null,
            'required'              => false,
            'solid'                 => true,
            'clearable'             => false,
            'searchable'            => false,
            'placeholder'           => null,
            'dropdownParent'        => '',
            'disabled'              => false,
            'multiple'              => false,
            'data'                  => [],
            'default'               => null,
            'value'                 => null,
            'view'                  => 'DEFAULT', // DEFAULT | INLINE
            'input_size'            => 'col-lg-8',
            'label_size'            => 'col-lg-12',
            'text'                  => function($key, $value) {return null;},
            'values'                => function($key, $value) {return null;},
            'select'                => function($key, $value, $selected) {return false;},
            'selected'              => [], // for ajax
            'withImg'               => false,
        ], $options);

        $VALUE['id']            = $VALUE['id'] ?? $VALUE['name'];
        $VALUE['clearable']     = $VALUE['clearable'] ? 'true' : 'false';
        $VALUE['searchable']    = $VALUE['searchable'] ? 'false' : 'true';
        $VALUE['value']         = old($VALUE['name'], $VALUE['value']);
        $VALUE['name']          = $VALUE['name'] . ($VALUE['multiple'] ? '[]' : '');

    @endphp

    @if($VALUE['view'] == 'DEFAULT')
        @include('admin::components.input_particles.label' , ['VALUE' => $VALUE])
        @include('admin::components.input_particles.select', ['VALUE' => $VALUE])
    @elseif($VALUE['view'] == 'INLINE')
        <div class="row">
            @include('admin::components.input_particles.label' , ['VALUE' => $VALUE])
            <div class="form-group {{ $VALUE['input_size'] }}">
                @include('admin::components.input_particles.select', ['VALUE' => $VALUE])
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
