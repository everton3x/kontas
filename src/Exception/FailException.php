<?php

namespace Kontas\Exception;

use Exception;

/**
 * Ocasiona a saída.
 *
 * @author Everton
 */
class FailException extends Exception {
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
