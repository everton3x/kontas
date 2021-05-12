<?php

namespace Kontas\Exception;

use Exception;

/**
 * Algo inesperado mas que não precisa ocasionar a saída.
 *
 * @author Everton
 */
class WarnException extends Exception {
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null): \Exception {
        parent::__construct($message, $code, $previous);
    }
}
