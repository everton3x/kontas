<?php

/**
 * Funções de número e moeda.
 */

function formatNumber(float|int $number): string
{
    return number_format($number, 2, ',', '.');
}