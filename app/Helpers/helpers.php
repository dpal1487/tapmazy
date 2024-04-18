<?php

use Carbon\Carbon;

/**
 * Write code on Method
 *
 * @return response()
 */
if (!function_exists('convertYmdToMdy')) {
    function convertYmdToMdy($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('m-d-Y');
    }
}

/**
 * Write code on Method
 *
 * @return response()
 */
if (!function_exists('convertMdyToYmd')) {
    function convertMdyToYmd($date)
    {
        return Carbon::createFromFormat('m-d-Y', $date)->format('Y-m-d');
    }
}

if (!function_exists('createMessage')) {
    function createMessage($message)
    {
        return ['success' => true, 'message' => "$message succesfully created."];
    }
}

if (!function_exists('updateMessage')) {
    function updateMessage($message)
    {
        return ['success' => true, 'message' => "$message succesfully updated."];
    }
}
if (!function_exists('deleteMessage')) {
    function deleteMessage($message)
    {
        return ['success' => true, 'message' => "$message successfully deleted."];
    }
}
if (!function_exists('statusMessage')) {
    function statusMessage($message)
    {
        return ['success' => true, 'message' => "$message status changed."];
    }
}
if (!function_exists('errorMessage')) {
    function errorMessage()
    {
        return ['success' => false, 'message' => "Opps something went wrong!"];
    }
}

if (!function_exists('restoreMessage')) {
    function restoreMessage($message)
    {
        return ['success' => true, 'message' => "$message succesfully restore."];
    }
}
