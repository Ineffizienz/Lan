<?php

function is_multi($a)
{
    $rv = array_filter($a, 'is_array');
    return count($rv)>0;
}