<?php

function gmail_connect($username, $password) {
  	$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
	return imap_open($hostname, $username, $password);
}

function gmail_today($inbox) {
	$e =  imap_search($inbox, 'ON "' . date ('d M Y') . '"');
	rsort($e);
	return $e;
}

function gmail_read($inbox, $email_number) {
	$overview = imap_fetch_overview($inbox,$email_number,0);

	$body = imap_fetchbody($inbox, $email_number, 1.1);

	if (!$body) {
	    $body = imap_fetchbody($inbox, $email_number, 1);
	}
			
	if (!$body) {
	    $body = imap_fetchbody($inbox, $email_number, 2);
	}
			
	$body = trim(substr(quoted_printable_decode($body), 0, 1000));

	$overview[0]->body = $body;

	return $overview[0];
}
