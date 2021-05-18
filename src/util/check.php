<?php

namespace kontas\util;

/**
 * Description of check
 *
 * @author Everton
 */
class check {
    public static function testIfKeyExists(array $keys, array $data): string {
        foreach ($keys as $key) {
            if (key_exists($key, $data) === false) {
                return $key;
            }
        }

        return '';
    }

    public static function testIfValueNotIsEmpty(array $keys, array $data): string {
        foreach ($keys as $key) {
            if (mb_strlen($data[$key]) === 0) {
                return $key;
            }
        }

        return '';
    }
}
