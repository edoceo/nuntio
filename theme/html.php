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
<script src="//gcdn.org/jquery/1.9.1/jquery.js" type="text/javascript"></script>
<script src="/js/nuntio.js" type="text/javascript"></script>
<?php
if (!empty($_ENV['use-websocket'])) {
    echo '<script src="/js/nuntio-websocket.js" type="text/javascript"></script>';
} else {
    echo '<script src="/js/nuntio-poll.js" type="text/javascript"></script>';
}
?>
<link href="http://radix.edoceo.com/css/radix.css" rel="stylesheet">
<link href="/nuntio.css" rel="stylesheet">
</head>

<body>
<header id="head">
<nav>
<ul>
<li><a href="/">Nuntio</a></li>
<li><a href="/~<?php echo $_ENV['room_name']; ?>">~<?php echo $_ENV['room_name']; ?></a></li>
<li><a href="/~<?php echo $_ENV['room_name']; ?>#task">#task</a></li>
<li><a href="/~<?php echo $_ENV['room_name']; ?>#policy">#policy</a></li>
<li><a href="/~<?php echo $_ENV['room_name']; ?>#sop">#sop</a></li>
</nav>
</header>

<div id="main">
<?php
echo $this->body;
?>
</div>

<footer>
<a href="http://nunt.io/">Nuntio</a> built by <a href="http://edoceo.com/">edoceo</a>.
</footer>

</body>
</html>

