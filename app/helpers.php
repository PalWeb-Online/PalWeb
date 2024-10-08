<?php

function audify($translit)
{
    $find = [' ', '-'];
    $fix = ['_', ''];

    return str_replace($find, $fix, $translit);
}
