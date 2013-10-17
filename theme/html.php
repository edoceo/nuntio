<?php
/**

*/

if (!empty($_GET['ws']) && ('true' == $_GET['ws'])) {
    $_ENV['use-websocket'] = true;
}

echo "<!doctype html>\n";
echo "<html>\n";
?>
<head>
<title>Nuntio - Simplified Development Team Communications</title>
<script src="//gcdn.org/jquery/1.10.2/jquery.js" type="text/javascript"></script>
<script src="/js/nuntio.js" type="text/javascript"></script>
<?php
if (!empty($_ENV['use-websocket'])) {
    echo '<script src="/js/nuntio-websocket.js" type="text/javascript"></script>';
} else {
    echo '<script src="/js/nuntio-poll.js" type="text/javascript"></script>';
}
?>
<link href="http://radix.edoceo.com/css/radix.css" rel="stylesheet">
<link href="//gcdn.org/font-awesome/3.2.1/font-awesome.css" rel="stylesheet">
<link href="/css/nuntio.css" rel="stylesheet">
</head>

<body>
<header id="head">
<nav>
<ul>
<li><a href="/">Nuntio</a></li>
<?php
if (!empty($_ENV['room_name'])) {

	$link = sprintf('~%s',$_ENV['room_name']);
	echo '<li><a href="/' . $link . '">' . $link . '</a></li>';

	// Room Tags
	if (!empty($_ENV['room_name'])) {
		if (is_array($_ENV['room_tags'])) {
			foreach ($_ENV['room_tags'] as $tag) {
				echo '<li><a href="/' . $link . '#' . $tag .'">#' . $tag . '</a></li>';
			}
		}
	}
}
?>
</nav>
</header>

<div id="main">
<?php
echo $this->body;
?>
</div>

<footer>
<p><a href="http://nunt.io/">Nuntio</a> is &copy; 2013 <a href="http://edoceo.com/">edoceo</a>.</p>
<p>Code is freely available <a href="https://github.com/edoceo/nuntio">github.com/edoceo/nuntio</a>.</p>
</footer>

</body>
</html>

