<?php

function parse_sql_timestamp($timestamp, $format = 'm/d/Y')
{
    $date = new DateTime($timestamp);
    return $date->format($format);
}

function parse_sql_timestamp_full($timestamp)
{
	return parse_sql_timestamp($timestamp, "D M d Y");
}

# Debugging tools
function pre_print($array) 
{
	echo '<pre>' . print_r($array, TRUE) . '</pre>';
}

function pre_print_exit($array)
 {
	pre_print($array);
	exit;
}

function html_quotes($string) 
{
	return htmlentities($string, ENT_QUOTES);
}

function truncate_text($text, $limit, $elipses = '...', $break = ' ')
{
	if (strlen($text) > $limit)
	{
		$text = substr($text, 0, $limit);
		if (FALSE !== ($breakpoint = strrpos($text, $break)))
			$text = substr($text, 0, $breakpoint) . $elipses;
	}

	return $text;
}

function random_string($length = 9, $no_numbers = FALSE)
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	if ($no_numbers)
		$characters = preg_replace('/\d/', '', $characters);

    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}