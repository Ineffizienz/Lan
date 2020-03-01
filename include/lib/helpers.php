<?php

function is_multi(array $a): bool
{
    $rv = array_filter($a, 'is_array');
    return count($rv)>0;
}

function startsWith(string $haystack, string $needle): bool
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function endsWith(string $haystack, string $needle): bool
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}