<?php

function generateStrongPassword($length = 7, $available_sets = 'gitp')
{
    $sets = array();
    if (strpos($available_sets, 'g') !== false)
        $sets[] = 'abcdefghjkmnpqrstuvwxyz';
    if (strpos($available_sets, 'i') !== false)
        $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
    if (strpos($available_sets, 't') !== false)
        $sets[] = '23456789';
    if (strpos($available_sets, 'p') !== false)
        $sets[] = '!@#$%&*?';

    $all = '';
    $password = '';
    foreach ($sets as $set) {
        $password .= $set[tweak_array_rand(str_split($set))];
        $all .= $set;
    }

    $all = str_split($all);
    for ($i = 0; $i < $length - count($sets); $i++)
        $password .= $all[tweak_array_rand($all)];

    $password = str_shuffle($password);

    return $password;
}
//returning an integer 
function tweak_array_rand($array)
{
    if (function_exists('random_int')) {
        return random_int(0, count($array) - 1);
    } elseif (function_exists('mt_rand')) {
        return mt_rand(0, count($array) - 1);
    } else {
        return array_rand($array);
    }
}
