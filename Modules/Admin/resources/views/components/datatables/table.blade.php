@isset($options)
    @php
        $VALUE = array_merge([
            'id'                => 'data-table',
            'class'             => 'table border table-rounded table-hover table-row-bordered table-bordered gy-3 gs-5',
            'url'               => route('base.empty_data'),
            'method'            => GET_METHOD,
            'withExportButton'  => true,
            'withID'            => true,
            'withAction'        => true,
            'withCheckbox'      => false,
            'withCreatedAt'     => false,
            'createdAtColumn'   => trans('admin::datatable.base_columns.created_at'),
            'withTrash'         => true,
            'filter'            => false,
            'filterModalId'     => 'data-table-filter',
            'orderType'         => 'desc',
            'data'              => [],
            'search'            => false, // Data table search input (top right) Its Data table Default search input (usually top right and used in internal pages not in list pagea)
        ], $options);

        $tableId        = "#" . $VALUE['id'];
        $orderIndex     = $VALUE['withCheckbox'] ? 1 : 0;
    @endphp

    <table id="{{$VALUE['id']}}" class="{{$VALUE['class']}}">
        <thead class="bg-secondary">
            <tr class="fw-bold fs-6">

                @if($VALUE['withCheckbox'])
                    <th style="width: 25px" class="not-export">
                        <label class="form-check form-check-custom form-check-solid form-check-sm">
                            <input class="form-check-input check-all-datatable-items" type="checkbox" value="all"/>
                        </label>
                    </th>
                @endif

                <th @class(['d-none' => ! $VALUE['withID'], 'w-50px'])> # </th>

                @if (isset($columns))
                    {!! $columns !!}
                @endif

                @if ($VALUE['withCreatedAt'])
                    <th> {{ $VALUE['createdAtColumn'] }} </th>
                @endif

                @if ($VALUE['withAction'])
                    <th class="w-50px not-export"> @lang('admin::datatable.base_columns.actions') </th>
                @endif
            </tr>
        </thead>
    </table>

    @once
        @push('script')
            <script>
                // Init datatable buttons
                let datatableButtons = '';
                @if ($VALUE['withExportButton'] && $VALUE['search'])
                    datatableButtons = `<'row'<'col-sm-12 col-md-6 mb-5'B><'col-sm-12 col-md-6 mb-5'f>>`;
                @elseif ($VALUE['withExportButton'])
                    datatableButtons = `<'row'<'col-sm-12 col-md-6 mb-5'B>>`;
                @elseif ($VALUE['search'])
                    datatableButtons = `<'row'<'col-sm-12 col-md-6 mb-5'F>>`;
                @endif

                datatableButtons += `
                    <'row'<'col-sm-12'tr>>
                    <'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-2'i><'col-sm-12 col-md-5'p>>
                `;

                $(document).ready(function () {
                    // Init datatable
                    let datatable = $("{{$tableId}}").DataTable({
                        dom             : datatableButtons,
                        deferRender     : true,
                        serverSide      : true,
                        responsive      : true,
                        pagingType      : "simple_numbers",
                        processing      : true,
                        stateSave       : false,
                        searchDelay     : 1000,
                        order           : [["{{$orderIndex}}", '{{$VALUE['orderType']}}']],
                        language        : {
                            url: "{{trans('admin::datatable.datatable')}}",
                        },
                        @if ($VALUE['withExportButton'])
                            buttons: {
                                buttons: [
                                    {
                                        extend          : 'excel',
                                        text            : '<i class="fas fa-file-excel"></i>',
                                        titleAttr       : 'Excel',
                                        className       : 'datatable-btn',
                                        exportOptions   : {
                                            columns     : ':not(.not-export)'
                                        },
                                    },
                                    {
                                        extend          : 'pdf',
                                        text            : '<i class="fas fa-file-pdf"></i>',
                                        titleAttr       : 'PDF',
                                        className       : 'datatable-btn',
                                        exportOptions   : {
                                            columns     : ':not(.not-export)'
                                        },
                                    },
                                    {
                                        extend          : 'print',
                                        text            : '<i class="fas fa-print"></i>',
                                        titleAttr       : 'Print',
                                        className       : 'datatable-btn',
                                        exportOptions   : {
                                            columns     : ':not(.not-export)'
                                        },
                                    },
                                    {
                                        text        : '<i class="fas fa-sync-alt"></i>',
                                        titleAttr   : "{{trans('admin::datatable.buttons.refresh')}}",
                                        className   : 'datatable-btn',
                                        action      : function ( e, dt, node, config ) {
                                            dt.ajax.reload();
                                        }
                                    },
                                ]
                            },
                        @endif
                        drawCallback: function () {
                            KTMenu.createInstances();

                            $('.kt_docs_jstree_').jstree({
                                "core" : {
                                    "themes" : {
                                        "responsive": false
                                    }
                                },
                                "types" : {
                                    "default" : {
                                        "icon" : "fa fa-shield text-primary"
                                    },
                                    "check" : {
                                        "icon" : "fa fa-check text-success"
                                    }
                                },
                                "plugins": ["types"]
                            });
                        },
                        rowCallback: function(row, data) {
                            if(! isEmpty(data.deleted_at)) {
                                $(row).addClass('table-danger');
                            } else if(! isEmpty(data.disabled_at)) {
                                $(row).addClass('table-secondary');
                            }
                        },
                        ajax:{
                            url  : '{{$VALUE['url']}}',
                            type : '{{$VALUE['method']}}',
                            data : function (d) {
                                @if ($VALUE['withTrash'])
                                    d.trash  = $('#switch-archive').is(':checked') ? 'show' : 'hide';
                                @endif
                                @if ($VALUE['method'] == 'POST')
                                    d._token = "{{csrf_token()}}";
                                @endif
                                @if($VALUE['filter'])
                                    d.advanced_search = $('#{{$VALUE['filterModalId']}}').first().serializeObject();
                                @endif
                                @foreach ($VALUE['data'] as $key => $value)
                                    d.{{$key}} = '{{$value}}';
                                @endforeach
                            },
                            error: function (xhr, error, code) {
                                return handleFailResponse(error);
                            }
                        },
                        columns: [
                            @if ($VALUE['withCheckbox'])
                                {
                                    data        : 'id',
                                    name        : 'id',
                                    orderable   : false,
                                    searchable  : false,
                                    className   : 'not-export',
                                    render      : function (data, type, row) {
                                        return datatableCheckbox(data);
                                    }
                                },
                            @endif
                            {
                                data        : 'id',
                                name        : 'id',
                                visible     : "{{$VALUE['withID']}}",
                            },
                            @if (isset($jsColumns))
                                {!! $jsColumns !!}
                            @endif
                            @if ($VALUE['withCreatedAt'])
                                {
                                    data : 'created_at_format',
                                    name : 'created_at_format',
                                    orderable: false,
                                    searchable: false,
                                    render: function (data, type, row, meta) {
                                        return `
                                            <span class="text-gray-600 fw-semibold">${data}</span>
                                        `;
                                    }
                                },
                            @endif
                            @if ($VALUE['withAction'])
                                {
                                    data        : 'actions',
                                    name        : 'actions',
                                    orderable   : false,
                                    searchable  : false,
                                    className   : 'not-export',
                                    render      : function (data, type, row) {
                                        return handleDatatableAction(data);
                                    }
                                }
                            @endif
                        ],
                    });

                    let lastSearchValue = '';

                    const debounceSearch = debounce(function() {
                        const searchValue = this.value.trim();
                        // Check if the search input is not empty and search
                        if (! isEmpty(searchValue)) {
                            datatable.search(searchValue).draw();
                            lastSearchValue = searchValue;
                        } else {
                            // If the search input is empty, check if the last search value is not empty and search
                            if (lastSearchValue !== '') {
                                datatable.search(searchValue).draw();
                                lastSearchValue = '';
                            }
                        }
                    }, 500);

                    $('#data-table-search').on('keyup', debounceSearch);

                    // Handle click on "Switch archive" toggle
                    $('#switch-archive').on('switchChange.bootstrapSwitch', function(event, state) {
                        // Clear selected IDs and reset checkboxes
                        @if($VALUE['withCheckbox'])
                            resetSelectedIds();
                        @endif

                        datatable.ajax.reload();
                    });

                    // Handle When Apply Filter
                    $('#{{$VALUE['filterModalId']}}').on('submit', function (e) {
                        e.preventDefault();
                        datatable.ajax.reload();
                    });

                    // Handle When Reset Filter
                    $('#reset-filter').on('click', function () {
                        $('#{{$VALUE['filterModalId']}}').trigger('reset');
                        $(this).closest('form').find('select').trigger('change');
                        datatable.ajax.reload();
                    });

                    // When the DataTable is redrawn, a page is changed, or any action occurs on the DataTable,
                    // the "Select All" checkbox should be unchecked and the state of individual checkboxes
                    // should be updated based on the current selections
                    datatable.on('draw.dt', function () {
                        @if($VALUE['withCheckbox'])
                            $('.check-item').each(function () {
                                let id = $(this).val();
                                this.checked = selectedIds.has(id);
                            });
                            updateSelectAllCheckbox();
                        @endif

                        refreshFsLightbox(); // Refresh Lightbox for images
                    });

                });
            </script>
        @endpush
    @endonce
@endisset
