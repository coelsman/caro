<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/tiktaktoe.css">

	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
</head>
<body>
	<div id="tiktaktoe"></div>
</body>
<script type="text/javascript" src="js/Game.js"></script>
<script type="text/javascript" src="js/Cell.js"></script>
<script type="text/javascript" src="js/Generator.js"></script>
<script type="text/javascript" src="js/Executor.js"></script>
<script type="text/javascript" src="js/Bot.js"></script>
<script type="text/javascript">

var generator = new Generator($('#tiktaktoe'));

generator.create();

</script>
</html>