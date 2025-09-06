{{-- Inite Js --}}
@include('admin::helpers.script.init')

{{-- Handle Notify --}}
<script>
    function hanldeNotify(response) {
        if(response.notify_type == 'toastr') {
            GLOBAL.TOASTR.INIT(response.message.type, response.message.title, response.message.description);
        } else {
            GLOBAL.SWAL.INIT(response.message.type, response.message.title, response.message.description);
        }
    }
</script>

{{-- Handle When User Press Try To Exit From Form Without Saving Changes --}}
<script>
    let changesMade = false;

    $('input, select, textarea').on('change', function() {
        if($(this).closest('form').hasClass(changeTrackingFormClass) ) {
            // check if input, select, textarea is not empty
            if(isNotEmpty($(this).val())) {
                changesMade = true;
            } else {
                changesMade = false;
            }
        }
    });

    $('a:not('+requestWithDialogSelector+')').on('click', function(e) {
        let href = $(e.currentTarget).attr('href');
        if(changesMade && href != 'javascript:;' && ! href.startsWith('#')) {
            $(this).addClass(requestWithDialogClass)
            $(this).data('dialog-title', "{{ trans('admin::confirmations.confirm.exit.title')}}");
            $(this).data('dialog-desc', "{!! trans('admin::confirmations.confirm.exit.desc')!!}");
            $(this).data('dialog-confirm-button', "{{trans('admin::confirmations.confirm.exit.confirm_btn')}}");
            $(this).data('dialog-cancel-button', "{{trans('admin::confirmations.cancel')}}");
        } else {
            $(this).removeClass(requestWithDialogClass);
        }

    });
    // window.addEventListener('beforeunload', function (e) {
    //     if (changesMade) {
    //         e.preventDefault(); // Cancel the event
    //         e.returnValue = ''; // Chrome requires this to be set
    //     }
    // });
</script>

{{-- Handle Ajax Request --}}
<script>
    // Handle Request with Dialog wihout ajax
    $(document).on('click', requestWithDialogSelector, function(e) {
        // If this element has class request-ajax, then don't do anything becasue it will be handled by another event
        if($(this).hasClass(requestAjaxClass)) {
            return;
        }

        e.preventDefault();

        let url                 = $(this).data('href') || $(this).attr('href');
        let elementId           = $(this).attr('id');
        let showCanceledDialog  = $(this).data('show-canceled-dialog');

        GLOBAL.CONFIRM_DIALOG.TITLE             = $(this).data('dialog-title');
        GLOBAL.CONFIRM_DIALOG.TEXT              = $(this).data('dialog-desc');
        GLOBAL.CONFIRM_DIALOG.CONFIRMBUTTONTEXT = $(this).data('dialog-confirm-button');
        GLOBAL.CONFIRM_DIALOG.CANCELBUTTONTEXT  = $(this).data('dialog-cancel-button');

        return GLOBAL.CONFIRM_DIALOG.INIT(showCanceledDialog, function() {
            return redirectToHref(url);
        });
    });

    //Handle Request with ajax with or without dialog
    $(document).on('click', requestAjaxSelector, function (e) {

        let withDialog          = $(this).attr('data-with-dialog');
        let method              = $(this).attr('data-method');
        let url                 = $(this).attr('data-href');
        let prevent             = $(this).attr('data-prevent');
        let key                 = $(this).attr('data-key-name');
        let value               = $(this).attr('data-key-value');
        let showCanceledDialog  = $(this).attr('data-show-canceled-dialog');
        let datatable           = $(this).closest('#kt_content_container').find('table');
        let data                = {};

        if(isTruth(prevent)) {
            e.preventDefault();
        }

        if(isNotEmpty(key) && isNotEmpty(value)) {
            // Check if key is ids convert value to array to send multiple ids to server else send as it is
            if(key == 'ids') {
                value = value.split(',');
            }

            data[key] = value;
        }

        if(isTruth(withDialog)) {
            GLOBAL.CONFIRM_DIALOG.RESET();

            return GLOBAL.CONFIRM_DIALOG.INIT(showCanceledDialog, function() {
                return handleAjaxRequest(url, method, data, datatable);
            });
        }

        return handleAjaxRequest(url, method, data);
    });

    // Handel Ajax Request
    function handleAjaxRequest(url, method, data = {}, datatable = null) {
        const csrfToken     = method.toUpperCase() == 'GET' ? null : $('meta[name="csrf-token"]').attr('content');
        const target        = document.querySelector("#root-page");
        const blockUI       = new KTBlockUI(target, spinnerOption);
        let reloadDatatable = false;

        if(isNotEmpty(datatable) && datatable.length > 0) {
            reloadDatatable = true;
            datatable       = datatable.DataTable();
        }

        if (csrfToken) {
            data._token = csrfToken;
        }

        $.ajax({
            type        : method,
            url         : url,
            data        : data,
            beforeSend  : function () {
                blockUI.block();
            },
            success: function (response) {
                if(response.success) {

                    if(reloadDatatable) {
                        datatable.ajax.reload(null, false);  // Reload the data and keep the current page
                    }

                    // check if data object has ids key then remove the selected ids
                    if(data.ids) {
                        resetSelectedIds();
                    }

                    if(isNotEmpty(response.redirect)) {
                        return redirectToHref(response.redirect);
                    }

                } else {
                    // error(response);
                }
                if(response.notify_type == 'toastr') {
                    GLOBAL.TOASTR.INIT(response.message.type, response.message.title, response.message.description);
                } else {
                    GLOBAL.SWAL.INIT(response.message.type, response.message.title, response.message.description);
                }
            },
            error: function (response) {
                let responseJson = response.responseJSON;

                if(isEmpty(responseJson)) {
                    return GLOBAL.TOASTR.INIT('error');
                }

                if(responseJson.notify_type || 'toastr' == 'toastr') {
                    return GLOBAL.TOASTR.INIT(responseJson.message.type || 'error', responseJson.message.title, responseJson.message.description);
                } else {
                    return GLOBAL.SWAL.INIT(responseJson.message.type || 'error', responseJson.message.title, responseJson.message.description);
                }
            }
        }).always(function () {
            blockUI.release();
            blockUI.destroy();
        });
    }

    // Redirect To Location
    function redirectToHref(url) {
        if(isEmpty(url)) {
            return;
        }
        return window.location.href = url
    }

</script>

{{-- Handle Form Submit --}}
<script>
    $(document).ready(function () {
        $(document).on('click', '#form-submit-ajax', function (e) {
            e.preventDefault();

            let form = $('.ajax-form').first();

            if(form.length > 0) {
                form.submit();
            }
        });

        $(document).on('submit', '.ajax-form', function (e) {
            e.preventDefault();

            let form                    = $(this);
            let url                     = form.attr('action');
            let method                  = form.attr('method');
            let data                    = new FormData(this);
            let checkForEmptyCheckbox   = form.hasClass('check-empty-checkbox');

            /*
            * The FormData object does not include checkbox fields that are not checked (i.e. their checked value is false).
            * So, if the checkbox is not selected, it will not be included in the data that is sent or printed in the console.
            */
            let addEmptyCheckbox        = form.hasClass('add-empty-checkbox');

            if(checkForEmptyCheckbox) {
                CheckForEmptyCheckbox(form, data);
            }

            if(addEmptyCheckbox) {
                addEmptyCheckboxToForm(form, data);
            }

            addDropdownValuesToForm(data);

            // print values of form in console
            // for (var pair of data.entries()) {
            //     console.log(pair[0]+ ', ' + pair[1]);
            // }

            return handleFormSubmit(form, url, method, data);
        });

        function addDropdownValuesToForm(data) {
            let dropzones = Dropzone.instances;
            dropzones.forEach(function(dropzone) {
                dropzone.files.forEach(function(file) {
                    if (file.upload && file.upload.filename) {
                        data.append(dropzone.options.paramName + '[]', file);
                    }
                });
            });
        }

        function CheckForEmptyCheckbox(form, data) {
            let formHasEmptyListOfCheckbox = true;

            if(form.find('input[type="checkbox"]').length == 0) {
                formHasEmptyListOfCheckbox = false;
            } else {
                form.find('input[type="checkbox"]').each(function () {
                    if($(this).is(':checked')) {
                        formHasEmptyListOfCheckbox = false;
                        return;
                    }
                });
            }

            if(formHasEmptyListOfCheckbox) {
                $inputName = form.find('input[type="checkbox"]').first().attr('name');
                data.append($inputName, []);
                // if(firstCheckBox) {
                //     $inputName = form.find('input[type="checkbox"]').first().attr('name');
                //     data.append($inputName, []);
                // } else {
                //     form.find('input[type="checkbox"]').each(function () {
                //         let inputName = $(this).attr('name');
                //         data.append(inputName, []);
                //     });
                // }
            }
        }

        function addEmptyCheckboxToForm(form, data) {
            form.find('input[type="checkbox"]').each(function () {
                let inputName = $(this).attr('name');

                // if form data has input name then replace it if is checked by 1 else 0
                if(data.has(inputName)) {
                    data.set(inputName, $(this).is(':checked') ? '1' : '0');
                } else {
                    data.append(inputName, $(this).is(':checked') ? '1' : '0');
                }

            });
        }

        function handleFormSubmit(form, url, method, data) {
            const button      = $("#form-submit-ajax").first();

            $.ajax({
                type: method,
                url: url,
                data: data,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    resetFormValidation(form);
                    if(button.length > 0) {
                        button.attr('data-kt-indicator', 'on');
                        button.attr('disabled', true);
                    }
                },

            }).done(function(response) {
                return handleSuccessResponse(response);
            }).fail(function (response) {
                return handleFailResponse(response);
            }).always(function () {
                if(button.length > 0) {
                    button.attr('data-kt-indicator', 'of');
                    button.attr('disabled', false);
                }
            });
        }

        function handleFailResponse(response) {
            let responseJson            = response.responseJSON;

            if(isEmpty(response) || isEmpty(responseJson)) {
                return GLOBAL.TOASTR.INIT('error');
            }

            hanldeNotify(responseJson);

            if(response.status == {{ $validationCode }}) {
                handleFormValidationInput(responseJson.errors);
            }
        }

        function handleSuccessResponse(response) {
            if(isNotEmpty(response.redirect)) {
                return window.location.href = response.redirect;
            }

            return window.location.reload();
        }

        function resetFormValidation(form) {
            form.find('.fv-plugins-message-container').remove();
            form.find('.is-invalid').removeClass('is-invalid');
            // form.find('.tab-pane').removeClass('active show');
            form.find('.invalid-label').removeClass('invalid-label');
            form.find('.btn-icon').removeClass('end-5');
        }

        function handleFormValidationInput(errors) {
            for (const key in errors) {
                if (errors.hasOwnProperty(key)) {
                    const element = errors[key];
                    // replace . with [ and ] to get the nested input name (ability.1.title => ability[1][title])
                    let newKey = key.replace(/\./g, '[');
                    if (newKey.includes('[')) {
                        newKey += ']';
                    }

                    let input         = $('[name="' + newKey + '"]');
                    if(input.length == 0) {
                        // if input not found then check for select2 input
                        input = $('[name="' + newKey + '[]"]');
                    }
                    const inputTab      = input.closest('.tab-pane');
                    const inputParent   = input.closest('.form-group');
                    const label         = inputParent.find('label').first();

                    // If Input Tab Exists Then Show It
                    if(inputTab.length > 0) {
                        inputTab.addClass('active show');
                        inputTab.siblings().removeClass('active show');

                        // get input tab id
                        let inputTabId = inputTab.attr('id');
                        let tabHeader  = inputTab.closest('.tab-content').siblings('.nav').find('a[href="#' + inputTabId + '"]');

                        if(tabHeader.length > 0) {
                            tabHeader.addClass('active');
                            tabHeader.parent().siblings().children().removeClass('active');
                        }
                    }

                    // If Input Has Icon Change Icon Position
                    if(input.hasClass('has-icon')) {
                        let icon = input.siblings('.btn-icon').first();
                        icon.addClass('end-5');
                    }

                    // Change Label Color .if label has any color class then remove it
                    label.addClass('invalid-label');

                    // Focus On Input
                    input.focus();
                    input.addClass('is-invalid');

                    // Append Error Message
                    inputParent.append('<div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback" style="font-weight:500">' + element[0] + '</div>');
                }
            }
        }
    });
</script>

{{-- Handle Datatable Action Buttons And Checkbox --}}
<script>
    let selectedIds = new Set(); // Use Set to store unique IDs

    function handleDatatableAction(data) {
        if(isEmpty(data) || isEmpty(handleDatatableActionItems(data))) {
            return '';
        }

        if(data.items.length == 1) {
            let item    = data.items[0];

            return `
                <div class="btn btn-light btn-active-light-primary btn-sm datatable-action-menu">
                    ` + renderDatatableActions(item, false) + `
                </div>
            ` ;
        }

        return `
            <a href="#" class="btn btn-light btn-active-light-primary btn-sm datatable-action-menu" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                <i class="${data.icon}"></i>
            </a>
            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-200px py-4" data-kt-menu="true">
                ` + handleDatatableActionItems(data) + `
            </div>
        `;
    }

    function handleDatatableActionItems(data) {
        let items = '';

        // Convert data.items to an array if it isn't one already
        const itemsArray = Array.isArray(data.items) ? data.items : Object.values(data.items);

        // Sort the array by 'order' key
        const sortedItems = itemsArray.sort((a, b) => a.order - b.order);

        for (const value of sortedItems) {
            items += renderDatatableActions(value);
        }

        return items;
    }

    function renderDatatableActions(value, withLabel = true)
    {
        let items = '';
        let label = '';

        if(withLabel) {
            label = `<span class="menu-text">${value.label}</span>`;
        }

        if(value.type == 'divider') {
            items += `<div class="separator my-2"></div>`;
        }
        else if(value.type == 'button' && (value.action == 'update' || value.action == 'view' || value.action == 'show_log')) {
            items +=  `
                <div class="menu-item px-3">
                    <a href="${value.route}" class="menu-link px-3">
                        <i class="${value.icon} me-2" style="color:${value.color}"></i>
                        ${label}
                    </a>
                </div>
            `;
        }
        else if(value.type == 'modal') {
            items +=  `
                <div class="menu-item px-3">
                    <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#${value.target_id}" onclick="getModalContent('${value.route}', '${value.model}', '${value.target_id}')">
                        <i class="${value.icon} me-2" style="color:${value.color}"></i>
                        ${label}
                    </a>
                </div>
            `;
        }
        else {
            let mdethod = (value.action == 'soft_delete' || value.action == 'hard_delete') ? 'DELETE' : 'POST';
            items +=  `
                <div class="menu-item px-3">
                    <a href="#" class="menu-link px-3 ${requestAjaxClass}" data-href="${value.route}" data-method="${mdethod}" data-with-dialog="${value.withConfirm}" data-prevent="${value.withConfirm}" data-key-name="model" data-key-value="${value.model}">
                        <i class="${value.icon} me-2" style="color:${value.color}"></i>
                        ${label}
                    </a>
                </div>
            `;
        }

        return items;
    }

    function datatableCheckbox(data) {
        if(isEmpty(data)) {
            return '';
        }
        return `
            <label class="form-check form-check-custom form-check-solid form-check-sm">
                <input class="form-check-input check-item" type="checkbox" value="${data}"/>
            </label>
        `;
    }

    // Handle "Select All" checkbox
    $(document).on('change', '.check-all-datatable-items', function () {

        let status  = this.checked;

        $('.check-item').each(function () {
            this.checked = status;
            if(status) {
                selectedIds.add($(this).val());
            } else {
                selectedIds.delete($(this).val());
            }
        });

        updateActionDropdown();
    });

    // Handle individual checkbox
    $(document).on('change', '.check-item', function () {
        let id = $(this).val();

        if (this.checked) {
            selectedIds.add(id);
        } else {
            selectedIds.delete(id);
        }

        updateActionDropdown();
        updateSelectAllCheckbox();
    });

    // Function to update the dropdown based on selected IDs
    function updateActionDropdown() {
        if (selectedIds.size > 0) {
            $('.action-dropdown-menu').removeClass('d-none');
        } else {
            $('.action-dropdown-menu').addClass('d-none');
        }

        $('#multi-action-dropdown').children().each(function() {
            $(this).attr('data-key-value', Array.from(selectedIds));
        });
    }

    // Function to update "Select All" checkbox based on current page checkboxes
    function updateSelectAllCheckbox() {
        let allChecked = $('.check-item').length > 0 && $('.check-item').length === $('.check-item:checked').length;
        $('.check-all-datatable-items').prop('checked', allChecked);
    }

    function resetSelectedIds() {
        selectedIds.clear();
        $('.check-item').prop('checked', false);
        $('.check-all-datatable-items').prop('checked', false);
        updateActionDropdown();
        updateSelectAllCheckbox(); // Ensure Select All checkbox is updated after reset
    }
</script>

{{-- Handle Modal In Datatable --}}
<script>
    let getModalContent = function (url, modelId, modalTarget) {
        setTimeout(() => {

            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    model: modelId,
                },
                beforeSend  : function () {
                    $('.view-modal-body').addClass('loading');
                    $('.modal-spinner').show();
                },
                dataType: 'json',
                success: function (response) {
                    $('.view-modal-body').html(response.view);
                },
                error: function () {
                    $('.view-modal-body').html('<p>{{ trans('admin::messages.web_response_messages.error_occured_while_fetching_data') }}</p>');
                },
            }).always(function () {
                $('.modal-spinner').hide();
                $('.view-modal-body').removeClass('loading');
            });
        }, 200);
    };
</script>

{{-- Handle Quill Text Editor --}}
<script>
    const quillTextEditors = document.querySelectorAll('.quill-text-editor');

    if(quillTextEditors.length > 0) {
        quillTextEditors.forEach(function (element) {
            let textId = element.id;

            let quill = new Quill(`#${textId}`, {
                modules: {
                    toolbar: toolbarOptions
                },
                theme: 'snow'
            });

            quill.on('text-change', debounce(function() {
                value = quill.root.innerHTML;
                var cleanedContent = value.replace(/(<p><br><\/p>|<p>\s*<\/p>|<br>|&nbsp;)/g, '').trim();
                $(`textarea[data-text-id="${textId}-textarea"]`).text(cleanedContent);
            }, 200));
        });
    }
</script>

{{-- Handle Select2 With Ajax --}}
<script>
    $(document).ready(function () {

        const select2Ajax = $('.select2-ajax');

        if(select2Ajax.length > 0) {
            select2Ajax.each(function () {
                let $selectElement  = $(this);
                let url             = $selectElement.attr('data-url');
                let placeholder     = $selectElement.attr('data-placeholder');
                let multiple        = $selectElement.attr('data-multiple');
                let dropdownParent  = $selectElement.attr('data-dropdown-parent');
                let allowClear      = $selectElement.attr('data-allow-clear');
                let disabled        = $selectElement.attr('data-disabled');
                let selectedData    = $selectElement.attr('data-selected');
                let withImg         = $selectElement.attr('data-with-img');

                // handle selected data
                if(isNotEmpty(selectedData)) {
                    selectedData = JSON.parse(selectedData);

                    selectedData.forEach(function (data) {
                        if(isNotEmpty(data)) {
                            let option = new Option(data.text, data.id, true, true);
                            $selectElement.append(option).trigger('change');
                        }
                    });
                }

                $selectElement.select2({
                    placeholder: placeholder,
                    allowClear: allowClear,
                    dropdownParent: dropdownParent,
                    disabled: disabled,
                    multiple: multiple,
                    language: {
                        noResults: function () {
                            return "{{ trans('admin::messages.select2_messages.no_results_found') }}";
                        },
                        errorLoading: function () {
                            return "{{ trans('admin::messages.select2_messages.error_loading_data') }}";
                        },
                        loadingMore: function () {
                            return "{{ trans('admin::messages.select2_messages.loading_more_data') }}";
                        },
                        maximumSelected: function (e) {
                            let count = args.maximum;
                            return "{{ trans('admin::messages.select2_messages.maximum_selected', ['count' => ':count']) }}".replace(':count', count);
                        },
                        searching: function () {
                            return "{{ trans('admin::messages.select2_messages.searching') }}";
                        },
                        removeAllItems: function () {
                            return "{{ trans('admin::messages.select2_messages.remove_all_items') }}";
                        }
                    },
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term,
                                page: params.page || 1,
                                is_select: true
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;

                            return {
                                results: data.results,
                                pagination: {
                                    more: (params.page * 10) < data.total
                                }
                            };
                        },
                        cache: true
                    },
                    templateResult: function (data) {
                        if (!data.id) {
                            return data.text;
                        }

                        let content = data.text;

                        if(withImg) {
                            let imageUrl = data.image ? data.image : "{{ config('admin.frontend.default_placeholder') }}";
                            content = `<img src="${imageUrl}" class="img-thumbnail" style="width: 60px; margin-right: 10px;" />` +
                                `<span class="fw-bolder">${data.text}</span>`;
                        }

                        let $result = $(`<span>${content}</span>`);

                        return $result;
                    },
                    templateSelection: function (data) {
                        return data.text || data.id;
                    }
                });

            });
        }
    });
</script>

{{-- Handle Dropzone --}}
<script>
    $(document).ready(function () {
        Dropzone.autoDiscover = false;
        const dropzones = $('.dropzone');

        if(dropzones.length > 0) {
            dropzones.each(function () {
                let $selectElement      = $(this);
                let id                  = $selectElement.attr('id');
                let name                = $selectElement.attr('data-name');
                let uploadUrl           = $selectElement.attr('data-upload-url');
                let maxFiles            = parseInt($selectElement.attr('data-max-files'), 10);
                let maxFilesize         = parseInt($selectElement.attr('data-max-filesize'), 10);
                let acceptedFiles       = $selectElement.attr('data-accepted-files');
                let autoProcessQueue    = $selectElement.attr('data-auto-process-queue');
                let addRemoveLinks      = $selectElement.attr('data-add-remove-links');
                let headerText          = $selectElement.attr('data-header-text');
                let uploadText          = $selectElement.attr('data-upload-text');
                let existingFiles       = $selectElement.attr('data-existing-files');
                let deleteUrl           = $selectElement.attr('data-delete-url');

                let dropzone = new Dropzone(`#${id}`, {
                    url: uploadUrl,
                    paramName: name,
                    maxFiles: maxFiles,
                    maxFilesize: maxFilesize,
                    acceptedFiles: acceptedFiles,
                    autoProcessQueue: autoProcessQueue,
                    addRemoveLinks: addRemoveLinks,
                    dictDefaultMessage: headerText,
                });

                if (existingFiles) {
                    existingFiles = JSON.parse(existingFiles);

                    // Convert the object to an array
                    const existingFilesArray = Object.values(existingFiles);

                    if (Array.isArray(existingFilesArray) && existingFilesArray.length > 0) {
                        existingFilesArray.forEach(function (file) {
                            // Create a mock file object
                            let mockFile = {
                                id      : file.id,
                                name    : file.file_name,
                                size    : file.size,
                                accepted: true,
                                status  : Dropzone.ADDED,
                                url     : file.url,
                            };

                            // Emit the events to show the existing file in Dropzone
                            dropzone.emit('addedfile'   , mockFile);
                            dropzone.emit('thumbnail'   , mockFile, file.url);
                            dropzone.emit('complete'    , mockFile);
                            dropzone.files.push(mockFile); // Add mock file to Dropzone's files array

                            if(addRemoveLinks) {
                                // Create and append the custom remove button for the existing file
                                let removeButton = Dropzone.createElement('<button class="dz-remove custom-remove-btn" style="cursor: pointer;">Remove</button>');
                                mockFile.previewElement.appendChild(removeButton);

                                // Handle click on the custom remove button
                                removeButton.addEventListener('click', function (e) {
                                    e.preventDefault();
                                    e.stopPropagation();

                                    // replace :id with the actual id of the dropzone element
                                    let url = deleteUrl.replace(':id', mockFile.id);

                                    // Show confirmation dialog before deleting
                                    GLOBAL.CONFIRM_DIALOG.INIT(true, function() {
                                        deleteMedia(mockFile, url, dropzone);
                                    });

                                });
                            }
                        });
                    }
                }

                dropzone.on('addedfile', function(file) {
                    if (this.files.length > maxFiles) {
                        // this.removeFile(this.files[0]);
                        this.removeFile(file);

                        GLOBAL.TOASTR.INIT(
                            'error',
                            "{{ trans('admin::messages.web_response_messages.reached_maximum_number_of_files') }}",
                            "{{ trans('admin::messages.web_response_messages.you_must_delete_files_first') }}".replace(':count', maxFiles)
                        );

                    }

                    if(addRemoveLinks) {
                        // Create and append the standard remove button for new files
                        let removeButton = Dropzone.createElement('<button class="dz-remove" style="cursor: pointer;">Remove</button>');
                        file.previewElement.appendChild(removeButton);

                        // Handle click on the standard remove button
                        removeButton.addEventListener('click', function (e) {
                            e.preventDefault();
                            e.stopPropagation();

                            // Remove the file from Dropzone without confirmation
                            this.removeFile(file);
                        }.bind(this));
                    }
                });

            });
        }
    });

    function deleteMedia(file, url, dropzone) {
        let data = {
            id: file.id,
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function (response) {
                if(response.success) {
                    GLOBAL.TOASTR.INIT(response.message.type, response.message.title, response.message.description);
                    file.previewElement.remove();
                    dropzone.removeFile(file);
                } else {
                    GLOBAL.TOASTR.INIT(response.message.type, response.message.title, response.message.description);
                }
            },
            error: function (response) {
                GLOBAL.TOASTR.INIT('error');
            }
        });
    }
</script>

{{-- Handle Update Language --}}
<script>
    $(document).on('click', '.update-auth-language', function (e) {
        let lang = $(this).data('language');

        $.ajax({
            type: 'POST',
            url: "{{ route('admin.profile.updateLanguage') }}",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                lang: lang,
            }
        });
    });
</script>

{{-- Handle Date Range Picker --}}
<script>
    $(document).ready(function () {
        var start   = $(".kt_daterangepicker").data("start-date");
        var end     = $(".kt_daterangepicker").data("end-date");
        start       = start ? moment(start, "YYYY-MM-DD") : moment().subtract(29, "days");
        end         = end ? moment(end, "YYYY-MM-DD") : moment();

        function cb(start, end) {
            $(".kt_daterangepicker").html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
        }

        $(".kt_daterangepicker").daterangepicker({
            startDate: start,
            endDate: end,
            opens: direction,
            showDropdowns: true,
            ranges: {
                "{{ trans('admin::strings.date_range_picker.ranges.today') }}"         : [moment(), moment()],
                "{{ trans('admin::strings.date_range_picker.ranges.yesterday') }}"     : [moment().subtract(1, "days"), moment().subtract(1, "days")],
                "{{ trans('admin::strings.date_range_picker.ranges.last_7_days') }}"   : [moment().subtract(6, "days"), moment()],
                "{{ trans('admin::strings.date_range_picker.ranges.last_30_days') }}"  : [moment().subtract(29, "days"), moment()],
                "{{ trans('admin::strings.date_range_picker.ranges.this_month') }}"    : [moment().startOf("month"), moment().endOf("month")],
                "{{ trans('admin::strings.date_range_picker.ranges.last_month') }}"    : [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")],
                "{{ trans('admin::strings.date_range_picker.ranges.this_year') }}"     : [moment().startOf("year"), moment().endOf("year")],
            },
            locale: @json(trans('admin::strings.date_range_picker.locale')),
        }, cb);

        cb(start, end);
    });
</script>

{{-- Handle Date Picker --}}
<script>
    $(document).ready(function () {
        const datePickerInputs = $('.date-picker-input');

        if(datePickerInputs.length == 0) {
            return;
        }
        datePickerInputs.each(function () {
            let input    = $(this);
            let mode     = input.data('mode');
            let format   = input.data('date-format');
            let withTime = input.data('with-time');
            let id       = input.attr('id');

            $('#' + id).flatpickr({
                enableTime: withTime,
                dateFormat: format,
                mode: mode,
                // time_24hr: true,
                // locale: {
                //     firstDayOfWeek: 1,
                //     weekdays: {
                //         shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                //         longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                //     },
                //     months: {
                //         shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                //         longhand: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                //     },
                // },
            })

        });
    });
</script>

{{-- Handle Phone Number intl-tel-input --}}
<script>
    $(document).ready(function () {
        const setupIntlTelInput = function(id, fullNumberName, value) {
            const input = document.querySelector("#" + id);
            const iti = window.intlTelInput(input, {
                // i18n: ar,
                initialCountry: "auto",
                strictMode: true,
                separateDialCode: true,
                hiddenInput: function(telInputName) {
                    return {
                        phone: fullNumberName,
                        country: "country_code"
                    };
                },
                geoIpLookup: callback => {
                    fetch("https://ipapi.co/json")
                    .then(res => res.json())
                    .then(data => callback(data.country_code))
                    .catch(() => callback("us"));
                },
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/23.0.11/js/utils.js"
            });

            const getFullNumber = function() {
                let fullNumber  = iti.getNumber();
                let countryData = iti.getSelectedCountryData();
                fullNumber = fullNumber.replace(/[^0-9+]/g, '');
                $('input[name="' + fullNumberName + '"]').val(fullNumber);
                $('input[name="country_code"]').val(countryData.dialCode);
            };

            input.addEventListener('input', getFullNumber);

            if (value) {
                $('input[name="' + fullNumberName + '"]').val(value);
            }
        };

        const intTelInputs = $('.intl-tel-input');

        if(intTelInputs.length == 0) {
            return;
        }

        intTelInputs.each(function (index) {
            const input = $(this);
            const fullNumberName = input.data('full-number-name');
            const value = input.data('value');

            setupIntlTelInput(input.attr('id'), fullNumberName, value);
        });
    });
</script>

{{-- Handle Input Mask --}}
<script>
    $(document).ready(function () {
        const emailMaskInputs = $('.email-mask-input');

        emailMaskInputs.each(function (index) {
            let input = $(this);
            let id = input.attr('id');
            Inputmask({
                mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[.*{2,6}][.*{1,2}]",
                greedy: false,
                onBeforePaste: function (pastedValue, opts) {
                    pastedValue = pastedValue.toLowerCase();
                    return pastedValue.replace("mailto:", "");
                },
                definitions: {
                    "*": {
                        validator: '[0-9A-Za-z!#$%&"*+/=?^_`{|}~\-]',
                        cardinality: 1,
                        casing: "lower"
                    }
                }
            }).mask('#' + id);
        });
    });
</script>

{{-- Handle Session Messages --}}
@if (session('message'))
    <script>
        let data = @json(session('message'));
        console.log(data);
        hanldeNotify(data);
    </script>
@endif
