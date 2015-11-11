<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/tiktaktoe.css">

	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
</head>
<body>
	<div class="wrap_table">
		<?php for ($i=0; $i < 21; $i++) { ?>
			<div class="col-4">
				<div class="r_table">
					<div class="ico ico_1"></div>
					<div class="ico ico_2"></div>
					<div class="mark"><?php echo $i+1; ?></div>
				</div>
			</div>
		<?php } ?>
	</div>
	<div class="wrap_online">
		
	</div>
	<div class="clearfix"></div>
	<div id="tiktaktoe" class="hide"></div>
</body>
<script type="text/javascript" src="js/Game.js"></script>
<script type="text/javascript" src="js/Cell.js"></script>
<script type="text/javascript" src="js/Generator.js"></script>
<script type="text/javascript" src="js/Executor.js"></script>
<script type="text/javascript" src="js/Bot.js"></script>
<script type="text/javascript">
var wsUrl = 'ws://localhost:9300',
		wsHandle = new WebSocket(wsUrl),
		generator = new Generator($('#tiktaktoe')),
		game = new Game();

// generator.create();

$('#tiktaktoe').on('click', '.tiktok_item', function () {
	var col = parseInt($(this).attr('col')),
			row = parseInt($(this).attr('row'));

	wsHandle.send(JSON.stringify([row, col]));
});

wsHandle.onmessage = function (ev) {
	console.log(ev);
	var data = JSON.parse(ev.data);
	console.log(data);
	console.log($('.tiktok_item[col="'+data[0]+'"][col="'+data[1]+'"]'));
	$('#tiktaktoe').find('.tiktok_item[row="'+data[0]+'"][col="'+data[1]+'"]').html('x');
}
</script>
</html>