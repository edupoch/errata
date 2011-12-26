<?php

function setLang($lang){
	if ($lang){
		setlocale(LC_MESSAGES, $lang);
		putenv("LANG=".$lang.".utf8");
		
		bindtextdomain("errata", "./locale");
		textdomain("errata");
		bind_textdomain_codeset("errata", 'UTF-8');
	}
}

function getIpAddress() {
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                    return $ip;
                }
            }
        }
    }
}

/*
    This function let us correctly use microseconds within date formats
    Seen here:
    http://stackoverflow.com/questions/169428/php-datetime-microseconds-always-returns-0
*/

function udate($format, $utimestamp = null)
{
    if (is_null($utimestamp))
        $utimestamp = microtime(true);

    $timestamp = floor($utimestamp);
    $milliseconds = round(($utimestamp - $timestamp) * 1000000);

    return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
}
?>