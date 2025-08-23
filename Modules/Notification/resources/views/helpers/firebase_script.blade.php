@php
    use Modules\Notification\Enums\NotificationStatuses;
    use Modules\Notification\Enums\NotificationChannels;
@endphp

{{-- Firebase Messaging --}}
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js"></script>

{{-- Get FCM Token And Send To Server --}}
<script>
    // For Firebase JS SDK v7.20.0 and later, measurementId is optional
    const firebaseConfig = {
        apiKey              : "{{ config('notification.firebase_config.api_key') }}",
        authDomain          : "{{ config('notification.firebase_config.auth_domain') }}",
        projectId           : "{{ config('notification.firebase_config.project_id') }}",
        storageBucket       : "{{ config('notification.firebase_config.storage_bucket') }}",
        messagingSenderId   : "{{ config('notification.firebase_config.messaging_sender_id') }}",
        appId               : "{{ config('notification.firebase_config.app_id') }}",
        measurementId       : "{{ config('notification.firebase_config.measurement_id') }}"
    };

    // Initialize Firebase
    const app       = firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/firebase-messaging-sw.js')
        .then(function(registration) {
            // console.log('Service Worker registered with scope:', registration.scope);
        }).catch(function(err) {
            console.log('Service Worker registration failed:', err);
        });

        // Listen for messages from the service worker
        navigator.serviceWorker.addEventListener('message', (event) => {
            if (event.data.type === 'UPDATE_NOTIFICATION_COUNT') {
                const countElement = document.getElementById('belled-notifications-count');

                if (countElement) {
                    showNotificationBell();
                    // Increment the count safely
                    const currentCount = parseInt(countElement.textContent) || 0;
                    countElement.textContent = currentCount + event.data.increment;
                }
            }
        });
    }

    // Request permission to send notifications
    function requestPermission() {

        messaging.requestPermission()
            .then(function() {
                // Get the token and send it to the server
                return messaging.getToken();
            })
            .then(function(token) {
                // console.log('Token:', token);
                // Send the token to your server via AJAX
                sendTokenToServer(token);
            })
            .catch(function(err) {
                console.log('Unable to get permission to notify.', err);
            });
    }

    // Handle incoming messages
    messaging.onMessage(function(payload) {
        console.log('Message received. ', payload);

        // Extract title and body from payload data
        const title     = payload.notification?.title || "New Notification";
        const body      = payload.notification?.body  || "You have a new message.";
        // const imageUrl  = payload.notification?.icon  || 'https://via.placeholder.com/100'; // Placeholder for image

        const container     = document.getElementById('kt_docs_toast_stack_container');
        const targetElement = document.querySelector('[data-kt-docs-toast="stack"]'); // Template element to clone

        // Clone the target element
        const newToast = targetElement.cloneNode(true);

        // Generate a unique ID for each toast to avoid conflicts
        const uniqueId = 'toast-' + Math.random().toString(36).substring(2, 9);
        newToast.id = uniqueId;

        // Update the content of the toast (title, body, and image if necessary)
        newToast.querySelector('.toast-title').textContent  = title;
        newToast.querySelector('.toast-body').textContent   = body;
        newToast.querySelector('.toast-time').textContent   = new Date().toLocaleTimeString();
        // newToast.querySelector('.toast-header img').src = imageUrl; // Assuming you want to update an image in the toast

        // Append the new toast to the container
        container.appendChild(newToast);

        // Initialize the new toast instance
        const toast = new bootstrap.Toast(newToast);

        // Show the new toast
        toast.show();

        $('#belled-notifications-count').text(parseInt($('#belled-notifications-count').text()) + 1);

        showNotificationBell();
    });

    function showNotificationBell() {
        $('#unread-notifications-count').removeClass('d-none');
        $('#mark-all-notifications-as-read').removeClass('d-none');
    }

    // Send token to server
    function sendTokenToServer(token) {
        // Send the token to your Laravel backend using an AJAX request
        fetch('{{ route('notification.saveFcmToken') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ token: token })
        }).then(response => {
            // console.log('Token saved successfully.');
        }).catch(error => {
            console.log('Error saving token:', error);
        });
    }

    // Call the function to request permission and get token
    requestPermission();
</script>

{{-- Show Notification In Bell --}}
<script>
    let currentPage     = 1;
    let isLoading       = false; // Prevent multiple requests while scrolling
    let hasMorePages    = true;

    // Fetch notifications on click of notification bell
    document.getElementById('notification-trigger').addEventListener('click', function() {
        if($(this).hasClass('show')) {
            return;
        }
        currentPage         = 1; // Reset current page
        hasMorePages        = true;
        const notifyCount   = parseInt($('#belled-notifications-count').text());

        fetchNotifications(currentPage);

        if(notifyCount > 0) {
            markNotificationsAsSeen(true);
        }
    });

    // Fetch notifications on scroll
    document.getElementById('notification-list').addEventListener('scroll', function() {
        const notificationList = document.getElementById('notification-list');

        if ((notificationList.scrollTop + notificationList.clientHeight + 2) >= notificationList.scrollHeight && !isLoading && hasMorePages) {
            currentPage++;
            fetchNotifications(currentPage);
        }
    });

    // Fetch notifications from server
    function fetchNotifications(page = 1) {
        const notificationList  = document.getElementById('notification-list');
        const notificationEmpty = document.getElementById('notification-list-empty');
        const notificationCount = document.getElementById('notification-count');
        const loadingSpinner    = $('#loading-spinner');
        let   isLoading         = true; // Set loading state

        loadingSpinner.removeClass('d-none');

        $.ajax({
            url: '{{ route("notification.notifications.adminWebNotifications") }}',
            type: 'GET',
            data: { page: page },
            success: function(response) {

                const notifications         = response.data.items || [];
                const responseHasMorePages  = response.data.pagination.has_more_pages;

                if (page === 1) {
                    clearNotifications(notificationList);
                }

                if (notifications.length > 0) {
                    if (notificationEmpty) {
                        notificationEmpty.classList.add('d-none');
                    }

                    notificationCount.textContent = `${response.data.pagination.total + ' {{ trans("admin::strings.alerts") }}'}`;

                    notifications.forEach(function(notification) {
                        appendNotification(notificationList, notification);
                    });

                } else if (page === 1) {
                    if (notificationEmpty) {
                        notificationEmpty.classList.remove('d-none');
                    }

                    notificationCount.textContent = '0 {{ trans("admin::strings.alerts") }}';
                }

                if(! responseHasMorePages) {
                    hasMorePages = false;
                }

                isLoading = false; // Reset loading state
                loadingSpinner.addClass('d-none'); // Hide spinner after success
            },
            error: function() {
                notificationEmpty.classList.remove('d-none');
                notificationEmpty.innerHTML = '<p class="text-center text-muted">{{ trans("admin::messages.web_response_messages.failed_to_fetch_notifications") }}</p>';
                isLoading = false; // Reset loading state on error
                loadingSpinner.addClass('d-none'); // Hide spinner after error
            }
        });
    }

    // Clear notifications
    function clearNotifications(notificationList) {
        notificationList.innerHTML = '';
    }

    // Append notification to list
    function appendNotification(notificationList, notification) {
        const notificationChannelId = notification.web_channel_id;
        const isRead                = notification.is_read ? '' : 'bg-light-primary notification-unread';
        const bullet                = notification.is_read ? '' : `<span class="bullet bullet-dot bg-primary h-5px w-5px me-5 bullet-notify" id="bullet-notify-${notificationChannelId}"></span>`;
        const url                   = notification.link ? notification.link : '#';

        // check if url is external or internal and add target attribute
        const urlParts      = url.split('/');
        if(isNotEmpty(urlParts[2])) {
            if(urlParts[2] === window.location.hostname) {
                var target = 'target="_self"';
            } else {
                var target = 'target="_blank"';
            }
        }else {
            var target = '';
        }


        const notificationHtml = `
            <a href="${url}" class="mark-as-notification-read-request" data-is-read=${notification.is_read} ${target}>
                <div class="d-flex flex-stack py-4 notification-item border-bottom ${isRead}" data-notification-channedl-id="${notificationChannelId}">
                    <div class="d-flex align-items-center ms-5">
                        <div class="symbol symbol-35px me-4">
                            <span class="symbol-label bg-light-primary">
                                <span class="svg-icon svg-icon-2 svg-icon-primary">
                                    <img src="${notification.icon}" alt="icon" class="h-100" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; ">
                                </span>
                            </span>
                        </div>
                        <div class="mb-0 me-2">
                            <div class="fs-7 text-gray-800 fw-bolder">${notification.title}</div>
                            <div class="text-gray-400 fs-8 text-break-all">${notification.body}</div>
                        </div>
                    </div>
                    <span class="badge badge-light fs-8">${notification.sent_at}</span>
                    ${bullet}
                </div>
            </a>
        `;
        notificationList.insertAdjacentHTML('beforeend', notificationHtml);
    }

    function markNotificationsAsSeen(updateAll) {
        updateAll = updateAll ? 1 : 0;

        $.ajax({
            url: '{{ route("notification.notifications.updateNotificationChannelStatus") }}',
            type: 'POST',
            data: {
                _token      : '{{ csrf_token() }}',
                status      : "{{ NotificationStatuses::SEEN }}",
                channel     : "{{ NotificationChannels::FCM_WEB }}",
                updateAll   : updateAll,
            },
            success: function() {
                $('#unread-notifications-count').addClass('d-none');
                $('#belled-notifications-count').text(0);
            },
            error: function() {}
        });
    }

    $(document).on('click', '.mark-as-notification-read-request', function(e) {
        e.preventDefault();
        const notificationItem      = $(this).find('.notification-item');
        const notificationUrl       = $(this).attr('href');
        const notificationChannelId = notificationItem.data('notification-channedl-id');

        if(notificationItem.hasClass('notification-unread')) {
            markNotificationAsRead(notificationChannelId, notificationItem, notificationUrl, false);
        } else {
            if(notificationUrl !== '#') {
                if($(this).attr('target') === '_blank') {
                    window.open(notificationUrl, '_blank');
                } else {
                    window.location.href = notificationUrl;
                }
            }
        }
    });

    function markNotificationAsRead(notificationChannelId = null, notificationItem = null, notificationUrl = '#', updateAll = false) {
        updateAll = updateAll ? 1 : 0;
        const unReadNotificationsElement = $('#belled-notifications-count');
        const notificationList           = $('#notification-list');

        if(updateAll) {
            requestData = {
                _token      : '{{ csrf_token() }}',
                status      : "{{ NotificationStatuses::READ }}",
                channel     : "{{ NotificationChannels::FCM_WEB }}",
                updateAll   : updateAll,
            };
        } else {
            requestData = {
                _token                  : '{{ csrf_token() }}',
                status                  : "{{ NotificationStatuses::READ }}",
                channel                 : "{{ NotificationChannels::FCM_WEB }}",
                notificationChannelId   : notificationChannelId,
            };
        }

        $.ajax({
            url: '{{ route("notification.notifications.updateNotificationChannelStatus") }}',
            type: 'POST',
            data: requestData,
            success: function() {
                if(updateAll) {
                    $('.notification-item').removeClass('bg-light-primary notification-unread');
                    $('#unread-notifications-count').addClass('d-none');
                    unReadNotificationsElement.text(0);
                    $('#mark-all-notifications-as-read').addClass('d-none');
                    $('.bullet-notify').remove();
                    notificationList.find('a').attr('data-is-read', true);
                } else {
                    notificationItem.removeClass('bg-light-primary notification-unread');

                    unReadNotificationsElement.text(parseInt(unReadNotificationsElement.text()) - 1);

                    notificationItem.parent().attr('data-is-read', true);

                    $(`#bullet-notify-${notificationChannelId}`).remove();

                    if(notificationUrl !== '#') {
                        if(notificationItem.parent().attr('target') === '_blank') {
                            window.open(notificationUrl, '_blank');
                        } else {
                            window.location.href = notificationUrl;
                        }
                    }

                    // if notification list children a tags are all read, mark all as read button should be hidden
                    if(notificationList.find('a').length === notificationList.find('a[data-is-read="true"]').length) {
                        $('#mark-all-notifications-as-read').addClass('d-none');
                    }

                    if(parseInt(unReadNotificationsElement.text()) < 0) {
                        unReadNotificationsElement.text(0);
                    }
                }
            },
            error: function() {
                // console.log('Failed to mark notification as read.');
            }
        });
    }

    // Mark all notifications as read using debounce
    const markAllNotificationsAsReadDebounce = debounce(function() {
        markNotificationAsRead(null, null, '#', true);
    }, 500);

    // Mark all notifications as read on click of mark all as read button
    $(document).on('click', '#mark-all-notifications-as-read', function(e) {
        e.preventDefault();
        markAllNotificationsAsReadDebounce();
    });
</script>
