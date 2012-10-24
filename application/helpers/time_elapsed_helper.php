<?php
/*
 * @author http://www.zachstronaut.com/posts/2009/01/20/php-relative-date-time-string.html
 * adapted as a CI Helper by NWW
 */

function time_elapsed($ptime)
{
	$etime = time() - (is_numeric($ptime) ? $ptime : strtotime($ptime, time()));
	
	if ($etime < 1)
		return 'less than 1 second';
	
	$a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
				30 * 24 * 60 * 60       =>  'month',
				24 * 60 * 60            =>  'day',
				60 * 60                 =>  'hour',
				60                      =>  'minute',
				1                       =>  'second'
	);
	
	foreach ($a as $secs => $str) {
		$d = $etime / $secs;
		if ($d >= 1) {
			$r = round($d);
			return $r . ' ' . $str . ($r > 1 ? 's' : '');
		}
	}
}