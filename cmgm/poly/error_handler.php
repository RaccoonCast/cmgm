<?php
// Set error handler
set_error_handler('api_error_handler');

function api_error_handler($errno, $errstr) {
    return api_error($errstr, $errno, 500);    
}

// Set uncaught exceptions handler    
set_exception_handler('api_exception_handler');

function api_exception_handler($exception) {
    return api_error($exception->getMessage(), $exception->getCode(), 500);
}

// Error/Exception helper
function api_error($error, $errno, $code) {
    // In production, you might want to suppress all these verbose errors
    // and throw a generic `500 Internal Error` error for all kinds of 
    // errors and exceptions.
    $errno = 500;
    $error = 'Internal Server Error!';

    http_response_code($code);
    header('Content-Type: application/json');

    return json_encode([
        'success' => false,
        'errno'   => $errno,
        'error'   => $error,
    ]);
}
?>