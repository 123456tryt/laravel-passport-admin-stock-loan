<?php
function createCode($user_id)
{
    static $source_string = 'E5FCDG3HQA4B1NOPIJ2RSTUV67MWX89KLYZ';
    $num = $user_id;
    $code = '';
    while ($num > 0) {
        $mod = $num % 35;
        $num = ($num - $mod) / 35;
        $code = $source_string[$mod] . $code;
    }
    if (empty($code[3]))
        $code = str_pad($code, 4, '0', STR_PAD_LEFT);
    return $code;
}

function decode($code)
{
    static $source_string = 'E5FCDG3HQA4B1NOPIJ2RSTUV67MWX89KLYZ';
    if (strrpos($code, '0') !== false)
        $code = substr($code, strrpos($code, '0') + 1);
    $len = strlen($code);
    $code = strrev($code);
    $num = 0;

    for ($i = 0; $i < $len; $i++) {
        $num += strpos($source_string, $code[$i]) * pow(35, $i);
    }

    return $num;
}


var_dump(createCode(999955), createCode(999956), createCode(999957), createCode(999958),
    createCode(999959), createCode(999960), createCode(999961), createCode(999962),
    createCode(999963), createCode(999964), createCode(999965), createCode(999966),
    createCode(999967), createCode(999968), createCode(999969), createCode(999970),
    createCode(999971), createCode(999972), createCode(999973), createCode(999974)
);
