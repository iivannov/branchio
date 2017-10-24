<?php

namespace Iivannov\Branchio\Exceptions;


class BranchioException extends \RuntimeException
{

    /**
     * Create a new exception based on a Bad Request error received
     *
     * @param \Throwable $exception
     * @return BranchioException
     */
    public static function makeFromResponse(\Throwable $exception)
    {
        $original = self::getOriginalBody($exception);

        if (isset($original->error->code) && isset($original->error->message)) {
            return new BranchioException($original->error->message, $original->error->code, $exception);
        }

        return new BranchioException('Unhandled Bad Response', 0, $exception);
    }

    /**
     * Try to get more information about the error by extracting
     * a valid json response from the original exception message
     *
     * @param \Throwable $exception
     * @return mixed|null
     */
    private static function getOriginalBody(\Throwable $exception)
    {
        $lines = explode(PHP_EOL, $exception->getMessage());

        $json = null;
        foreach ($lines as $line) {
            if ($json = json_decode($line)) {
                break;
            }
        }

        return $json;
    }
}