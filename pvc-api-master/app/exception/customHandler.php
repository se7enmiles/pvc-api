<?php
function handleException($e)
{
    $error = array(
        'status' => $e->getCode(),
        'message' => $e->getMessage(),
//        'file' => $e->getFile(),
//        'line' => $e->getLine(),
    );

    echo json_encode($error);

    error_log(json_encode($error), 0);
}

set_exception_handler("handleException");