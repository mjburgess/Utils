<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>GMail Reader</title>
<script type="text/javascript" src="http://davidwalsh.name/demo/mootools-1.2.2.js"></script>
<style type="text/css">
div.toggler {
  border: 1px solid #ccc;
	background: url(gmail2.jpg) 10px 12px #eee no-repeat;
	cursor: pointer;
	padding: 10px 32px;
}

div.toggler .subject {
	font-weight: bold;
}

div.read {
	color: #666;
}

div.toggler .from,div.toggler .date {
	font-style: italic;
	font-size: 11px;
}

div.body {
	padding: 10px 20px;
}
</style>

<script type="text/javascript">
    window.addEvent('domready', function() {
        var togglers = $$('div.toggler');
        if (togglers.length)
            var gmail = new Fx.Accordion(togglers, $$('div.body'));
        togglers.addEvent('click', function() {
            this.addClass('read').removeClass('unread');
        });
        togglers[0].fireEvent('click'); //first one starts out read
    });
</script>
</head>
<body>
<?php if(empty($_POST['username'])) { ?>

<form action="" method="post">
	<div>U: <input type="text" name="username" id="username" /></div>
	<div>P: <input type="password" name="password" id="password" /></div>
	<input type="submit" name="submit" value="submit" />
</form>

<?php } else {
	/* connect to gmail */
	$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	/* try to connect */
	$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
	
	/* grab emails */
	$emails = imap_search($inbox, 'ON "' . date ('d M Y') . '"');
	
	/* if emails are returned, cycle through each... */
	if($emails) {
		
		/* begin output var */
		$output = '';
		
		/* put the newest emails on top */
		rsort($emails);
		
		/* for every email... */
		foreach($emails as $email_number) {
			
			/* get information specific to this email */
			$overview = imap_fetch_overview($inbox,$email_number,0);
			
			$body = imap_fetchbody($imap, $i, 1.1);
			
			if (!$body) {
			    $body = imap_fetchbody($imap, $i, 1);
			}
			
			if (!$body) {
			    $body = imap_fetchbody($imap, $i, 2);
			}
			
			$body = trim(substr(quoted_printable_decode($body), 0, 100));

			
			/* output the email header information */
			$output.= '<div class="toggler '.($overview[0]->seen ? 'read' : 'unread').'">';
			$output.= '<span class="subject">'.$overview[0]->subject.'</span> ';
			$output.= '<span class="from">'.$overview[0]->from.'</span>';
			$output.= '<span class="date">on '.$overview[0]->date.'</span>';
			$output.= '</div>';
			
		        
		        $output.= '<div id="body">'.quoted_printable_decode($body)).'</div>';
        
			
		}
		
		echo $output;
	} 
	
	/* close the connection */
	imap_close($inbox);
}
?>


</body>
</html>
