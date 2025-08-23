<!-- Initie Const Variables -->
<script>
    const changeTrackingFormClass       = 'change-tracking-form';
    const changeTrackingFormSelector    = '.' + changeTrackingFormClass;
    const requestWithDialogClass        = 'request-with-dialog';
    const requestWithDialogSelector     = '.' + requestWithDialogClass;
    const requestAjaxClass              = 'request-ajax';
    const requestAjaxSelector           = '.' + requestAjaxClass;
</script>

<!-- Initie Toaster -->
<script>
    let direction = "{{$_DIR_}}" == 'ltr' ? 'right' : 'left';
    let rtlEnable = "{{$_DIR_}}" == 'ltr' ? false : true;

    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass":  "toastr-top-" + direction,
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "rtl": rtlEnable,
    };
</script>

<!-- Inite Spinner -->
<script>
    const spinnerOption = {
        overlayClass: "bg-danger bg-opacity-25",
        message: '<span class="loader"></span>',
    }
</script>

<!-- Inite Toggle Switch -->
<script>
    $('#switch-archive').bootstrapSwitch({
        size: 'small',  // Set the size of the switch ('small', 'normal', 'large')
        offText: "{{trans('admin::base.view_archive')}}",  // Text displayed when in the 'on' state
        onText: "{{trans('admin::base.hide_archive')}}",  // Text displayed when in the 'off' state
        onColor: 'danger',  // Color of the 'on' state
        offColor: 'primary-color',  // Color of the 'off' state
        handleWidth: 'auto'  // Width of the handle
    });
</script>

<!-- Initie Confirm Diyalog -->
<script>
    let GLOBAL = {};
    GLOBAL.CONFIRM_DIALOG = {};

    const defaultConfirmDialog = {
        ICON: 'warning',
        TITLE: "{{trans('admin::confirmations.confirm.base.title')}}",
        TEXT: "{!!trans('admin::confirmations.confirm.base.desc')!!}",
        CONFIRMBUTTONTEXT: "{{trans('admin::confirmations.yes')}}",
        CANCELBUTTONTEXT: "{{trans('admin::confirmations.no')}}",
        CONFIRMBUTTONCOLOR: '#F1416C',
        CANCELBUTTONCOLOR: '#009EF7'
    };

    GLOBAL.CONFIRM_DIALOG = { ...defaultConfirmDialog };

    GLOBAL.CONFIRM_DIALOG.RESET = function () {
        Object.assign(GLOBAL.CONFIRM_DIALOG, defaultConfirmDialog);
    };

    GLOBAL.CONFIRM_DIALOG.INIT = function (showCanceledDialog = true, callback, ...params)
    {
        Swal.fire({
            title: GLOBAL.CONFIRM_DIALOG.TITLE,
            text: GLOBAL.CONFIRM_DIALOG.TEXT,
            icon: GLOBAL.CONFIRM_DIALOG.ICON,
            showCancelButton: true,
            confirmButtonColor: GLOBAL.CONFIRM_DIALOG.CONFIRMBUTTONCOLOR,
            cancelButtonColor: GLOBAL.CONFIRM_DIALOG.CANCELBUTTONCOLOR,
            confirmButtonText: GLOBAL.CONFIRM_DIALOG.CONFIRMBUTTONTEXT,
            cancelButtonText: GLOBAL.CONFIRM_DIALOG.CANCELBUTTONTEXT,
        }).then((result) => {
            if (result.isConfirmed) {
                GLOBAL.CONFIRM_DIALOG.CONFIRM(callback, ...params);
            } else {
                if(showCanceledDialog)
                    GLOBAL.CONFIRM_DIALOG.CANCEL();
            }
        })
    }

    GLOBAL.CONFIRM_DIALOG.CONFIRM = function (callback, ...params) {
        if(isEmpty(params)) {
            return callback();
        }
        return callback(...params);
    }

    GLOBAL.CONFIRM_DIALOG.CANCEL = function () {
        return GLOBAL.SWAL.INIT('error', "{{trans('admin::confirmations.confirm.base.canceled.title')}}", "{{trans('admin::confirmations.confirm.base.canceled.desc')}}")
    }

    // init toastr
    GLOBAL.TOASTR = {
        TITLE: {},
        DESC: {},
    };

    GLOBAL.TOASTR.TITLE = {
        SUCCESS     : "{{trans('admin::messages.response_message_types.success.title')}}",
        ERROR       : "{{trans('admin::messages.response_message_types.error.title')}}",
        WARINING    : "{{trans('admin::messages.response_message_types.warning.title')}}",
        INFO        : "{{trans('admin::messages.response_message_types.info.title')}}",
    };

    GLOBAL.TOASTR.DESC = {
        SUCCESS     : "{{trans('admin::messages.response_message_types.success.description')}}",
        ERROR       : "{{trans('admin::messages.response_message_types.error.description')}}",
        WARINING    : "{{trans('admin::messages.response_message_types.warning.description')}}",
        INFO        : "{{trans('admin::messages.response_message_types.info.description')}}",
    };


    GLOBAL.TOASTR.INIT = function (type, title = '', description = '') {
        switch(type) {
            case 'success':
                toastr.success(description || GLOBAL.TOASTR.DESC.SUCCESS, title || GLOBAL.TOASTR.TITLE.SUCCESS);
                break;
            case 'error':
                toastr.error(description || GLOBAL.TOASTR.DESC.ERROR, title || GLOBAL.TOASTR.TITLE.ERROR);
                break;
            case 'warning':
                toastr.warning(description || GLOBAL.TOASTR.DESC.WARINING, title || GLOBAL.TOASTR.TITLE.WARINING);
                break;
            case 'info':
                toastr.info(description || GLOBAL.TOASTR.DESC.INFO, title || GLOBAL.TOASTR.TITLE.INFO);
                break;
            default:
                toastr.error(description || GLOBAL.TOASTR.DESC.ERROR, title || GLOBAL.TOASTR.TITLE.ERROR);
        }
    }

    GLOBAL.SWAL = {
        TITLE: {},
        DESC: {},
    };

    GLOBAL.SWAL.TITLE = GLOBAL.TOASTR.TITLE;
    GLOBAL.SWAL.DESC  = GLOBAL.TOASTR.DESC;

    GLOBAL.SWAL.INIT = function (type, title = '', description = '') {
        switch(type) {
            case 'success':
                Swal.fire({
                    title: title || GLOBAL.SWAL.TITLE.SUCCESS,
                    text: description || GLOBAL.SWAL.DESC.SUCCESS,
                    icon: type,
                    confirmButtonText: "{{trans('admin::base.close')}}"
                })
                break;
            case 'error':
                Swal.fire({
                    title: title || GLOBAL.SWAL.TITLE.ERROR,
                    text: description || GLOBAL.SWAL.DESC.ERROR,
                    icon: type,
                    confirmButtonText: "{{trans('admin::base.close')}}"
                })
                break;
            case 'warning':
                Swal.fire({
                    title: title || GLOBAL.SWAL.TITLE.WARINING,
                    text: description || GLOBAL.SWAL.DESC.WARINING,
                    icon: type,
                    confirmButtonText: "{{trans('admin::base.close')}}"
                })
                break;
            case 'info':
                Swal.fire({
                    title: title || GLOBAL.SWAL.TITLE.INFO,
                    text: description || GLOBAL.SWAL.DESC.INFO,
                    icon: type,
                    confirmButtonText: "{{trans('admin::base.close')}}"
                })
                break;
            default:
                Swal.fire({
                    title: title || GLOBAL.SWAL.TITLE.ERROR,
                    text: description || GLOBAL.SWAL.DESC.ERROR,
                    icon: type || 'error',
                    confirmButtonText: "{{trans('admin::base.close')}}"
                })
        }
    }
</script>

{{-- Initie Debounce --}}
<script>
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            const context = this;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }
</script>

{{-- Initie Quill Editor --}}
<script>
    const toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
        ['blockquote', 'code-block'],
        ['link', 'image', 'video', 'formula'],

        [{ 'header': 1 }, { 'header': 2 }],               // custom button values
        [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
        [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
        [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
        [{ 'direction': '{{ $_DIR_ }}' }],                         // text direction

        [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

        [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
        [{ 'font': [] }],
        [{ 'align': [] }],

        ['clean']                                         // remove formatting button
    ];
</script>
