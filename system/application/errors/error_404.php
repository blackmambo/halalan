<?php header("HTTP/1.1 404 Not Found"); ?>
<html>
<head>
<title>404 Page Not Found</title>
<style type="text/css">

body {
background-color:	#fff;
margin:				40px;
font-family:		Lucida Grande, Verdana, Sans-serif;
font-size:			12px;
color:				#000;
}

#content  {
border:				#999 1px solid;
background-color:	#fff;
padding:			20px 20px 12px 20px;
}

h1 {
font-weight:		normal;
font-size:			14px;
color:				#990000;
margin: 			0 0 4px 0;
}
</style>
</head>
<body>
	<div id="content">
		<h1><?php echo $heading; ?></h1>
		<?php echo $message; ?>
		<?php
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
			$protocol = 'https';
		else
			$protocol = 'http';
		$base_url = trim($protocol . "://$_SERVER[HTTP_HOST]" . dirname($_SERVER['SCRIPT_NAME']), '/') . '/';
		?>
		<p>If you haven't installed Halalan yet, please <a href="<?php echo $base_url; ?>install">install</a> it now.</p>
	</div>
</body>
</html>
