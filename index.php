<?php require("class/main.php") ?>
<html>
<head>
	<title>Jacobsen Photography Seattle, WA</title>
	<link href='http://fonts.googleapis.com/css?family=Neucha' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="<?= $main->home ?>css/styles.css">
	<script src="http://code.jquery.com/jquery-1.7.min.js"></script>
	<script src="<?= $main->home ?>js/showdown.js" type="text/javascript" charset="utf-8" async defer></script>
	<script src="<?= $main->home ?>js/script.js" type="text/javascript" charset="utf-8" async defer></script>
</head>
<body>
	<nav>
		<a class="logo" href="<?= $main->home ?>" title="jacobsen photography">Jacobsen Photography</a>
		<?= $main->getNav() ?>
	</nav>
	<div id="viewport">
		<?= $main->getContent(); ?>	
	</div><!-- /viewport -->
</body>
</html>