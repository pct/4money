<?php

class Haanga_Extension_Filter_Substr
{
    public static function generator($compiler, $args)
    {
        if (count($args) != 2) {
            $cmp->Error("substr parameter must have one param");
        }
        if (!isset($args[1]['string'])) {
            $cmp->Error("substr parameter must be a string");
        }
        list($start, $end) = explode(",", $args[1]['string']);
		if(function_exists('mb_substr')){
			return hexec('mb_substr', $args[0],  (int)$start, (int)$end, 'UTF-8');
		}
		return hexec('substr', $args[0],  (int)$start, (int)$end);
    }
}
