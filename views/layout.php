<!DOCTYPE html>
<html>
<head>
	<title><? echo $title?></title>
</head>
<body>
<?php echo $heading_content; ?>
<?php echo $body_content; ?>

<?
	//print Flight::request()->getBody();
	//print Flight::jsonp(array('id' => 123), 'q');
?>
</body>
</html>