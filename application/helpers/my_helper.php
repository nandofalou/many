<?php

if (!function_exists('dd')) {
	function dd($data)
	{
		echo "<pre style='background-color: #111;color: #ff0;font-family: monospace; padding: 0.4rem;'><code>";
		echo date('Y-m-s H:i:s');
		echo "<hr/>";
		var_export($data);
		echo "\n<code></pre>\n";
		die('<hr/>');
	}
}


if (!function_exists('getRealIPAddr')) {

	function getRealIPAddr()
	{
		//check ip from share internet
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		//to check ip is pass from proxy
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return $ip;
	}
}
