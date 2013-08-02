<?php require 'email.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>jQuery UI Accordion - Default functionality</title>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(function() {
    $( "#accordion" ).accordion();
  });
  </script>
</head>

<body>
<?php if(empty($_POST['username'])): ?>

<form action="" method="post">
	<div>U: <input type="text" name="username" id="username" /></div>
	<div>P: <input type="password" name="password" id="password" /></div>
	<input type="submit" name="submit" value="submit" />
</form>

<?php else:  ?>
<div id="accordion"> 
<?php $emails = gmail_today($inbox = gmail_connect($_POST['username'], $_POST['password'])); ?>

<?php if(!$emails): ?> 
	<h1>No Emails Today!</h3> 
<?php endif; ?>

<?php foreach($emails as $email_number): $email = gmail_read($inbox, $email_number); ?>
<h3><?php echo $email->subject; ?> <em>from, <?php echo $email->from; ?>, on <?php echo $email->date; ?></em></h3>
<div><p>
<?php echo $email->body; ?></p></div>
<?php endforeach; ?>
	</div>
<?php endif; ?>


</body>
</html>
