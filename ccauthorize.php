<?php

function sendDatagram($num, $date, $total, $name)
{
	$host="udp://blitz.cs.niu.edu";
	$port="4445";

	$fp=fsockopen($host, $port, $errno, $errstr);

	if (!fp)
	{
		die("errstr ($errno)<br>");
	}

	$message="$num:$date:$total:$name";

	fwrite($fp, $message) or die("Could not send.<br>");

	$response=fread($fp, 1024);

	fclose($fp);

	return $response;
}

