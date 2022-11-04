<?php

/**
 *
 */
class errorHandler
{


    public static function handleException(Throwable $exception): void
    {

        http_response_code(500);
        $array = [

        "code" => $exception->getCode(),
        "message" => $exception->getMessage(),
        "file" => $exception->getFile(),
        "line" => $exception->getLine()
        ];

        $result = json_encode($array);
        echo $result;
    }
}
