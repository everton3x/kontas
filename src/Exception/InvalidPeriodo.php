<?php

namespace Kontas\Exception;

/**
 * Quando um período no formato aaaa-mm tem ano menor que 2000 e/ou mês fora do intervalo 1 ~ 12.
 *
 * @author Everton
 */
class InvalidPeriodo extends \Exception {
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null): \Exception {
        return parent::__construct($message, $code, $previous);
    }
}
