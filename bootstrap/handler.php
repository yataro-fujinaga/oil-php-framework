<?php
declare(strict_types = 1);

set_error_handler('error_exception_handler');
set_exception_handler('exception_handler');

/**
 * Converting errors to ErrorException
 *
 * @throws ErrorException
 */
function error_exception_handler(int $severity, string $message, string $file, int $line): void
{
    if (!(error_reporting() & $errno)) {
        return;
    }

    throw new ErrorException($message, 0, $severity, $file, $line);
}

/**
 * @param Throwable $exception
 */
function exception_handler(Throwable $exception): void
{
    $format = 'Uncaught exception: %s, file: %s, line: %s';
    echo sprintf($format, $exception->getMessage(), $exception->getFile(), $exception->getLine());
}
