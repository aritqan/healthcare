<?php

if(!defined('READ_ACTION')) {
    define('READ_ACTION', 'read');
}

if(!defined('VIEW_ACTION')) {
    define('VIEW_ACTION', 'view');
}

if(!defined('CREATE_ACTION')) {
    define('CREATE_ACTION', 'create');
}

if(!defined('UPDATE_ACTION')) {
    define('UPDATE_ACTION', 'update');
}

if(!defined('DISABLE_ACTION')) {
    define('DISABLE_ACTION', 'disable');
}

if(!defined('ENABLE_ACTION')) {
    define('ENABLE_ACTION', 'enable');
}

if(!defined('SOFT_DELETE_ACTION')) {
    define('SOFT_DELETE_ACTION', 'soft_delete');
}

if(!defined('HARD_DELETE_ACTION')) {
    define('HARD_DELETE_ACTION', 'hard_delete');
}

if(!defined('RESTORE_ACTION')) {
    define('RESTORE_ACTION', 'restore');
}

if(!defined('VIEW_TRASH_ACTION')) {
    define('VIEW_TRASH_ACTION', 'view_trash');
}

if(!defined('SHOW_LOG_ACTION')) {
    define('SHOW_LOG_ACTION', 'show_log');
}

if(!defined('CRUD_TYPES')) {
    define('CRUD_TYPES', [
        'read'          => READ_ACTION,
        'view'          => VIEW_ACTION,
        'create'        => CREATE_ACTION,
        'update'        => UPDATE_ACTION,
        'disable'       => DISABLE_ACTION,
        'enable'        => ENABLE_ACTION,
        'soft_delete'   => SOFT_DELETE_ACTION,
        'hard_delete'   => HARD_DELETE_ACTION,
        'restore'       => RESTORE_ACTION,
        'view_trash'    => VIEW_TRASH_ACTION,
        'show_log'      => SHOW_LOG_ACTION,
    ]);
}

if(!defined('POST_METHOD')) {
    define('POST_METHOD', 'POST');
}

if(!defined('PUT_METHOD')) {
    define('PUT_METHOD', 'PUT');
}

if(!defined('PATCH_METHOD')) {
    define('PATCH_METHOD', 'PATCH');
}

if(!defined('DELETE_METHOD')) {
    define('DELETE_METHOD', 'DELETE');
}

if(!defined('GET_METHOD')) {
    define('GET_METHOD', 'GET');
}

if(!defined('API_METHODS')) {
    define('API_METHODS', [
        POST_METHOD,
        PUT_METHOD,
        PATCH_METHOD,
        DELETE_METHOD,
        GET_METHOD,
    ]);
}

if(!defined('SUCCESS_STATUS')) {
    define('SUCCESS_STATUS', 'success');
}

if(!defined('FAILED_STATUS')) {
    define('FAILED_STATUS', 'failed');
}

if(!defined('API_LOG_STATUSES')) {
    define('API_LOG_STATUSES', [
        SUCCESS_STATUS,
        FAILED_STATUS,
    ]);
}

if(!defined('NUMBER_OF_RECORDS_PER_PAGE')) {
    define('NUMBER_OF_RECORDS_PER_PAGE', 10);
}
