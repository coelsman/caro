<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/tiktaktoe.css">

	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
</head>
<body>
	<div class="wrap_table">
		<?php for ($i=0; $i < 21; $i++) { ?>
			<div class="col-4">
				<div class="r_table" data-table="<?php echo base64_encode(base64_encode($i)); ?>">
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
		game = new Game(),
		cliend_id;

// generator.create();

$('#tiktaktoe').on('click', '.tiktok_item', function () {
	var col = parseInt($(this).attr('col')),
			row = parseInt($(this).attr('row'));

	wsHandle.send(JSON.stringify({
		type: 'game',
		data: {
			col: col,
			row: row
		}
	}));
});

wsHandle.onmessage = function (ev) {
	var data = JSON.parse(ev.data);
	
	switch (data.type) {
		case 'connect':
			onConnect(data.data);
			break;
		case 'disconnect': 
			onDisconnect(data.data);
			break;
		case 'game': 
			onGame(data.data);
			break;
	}
}

function onGame (wsData) {
	$('#tiktaktoe').find('.tiktok_item[row="'+wsData.row+'"][col="'+wsData.col+'"]').html('x');
}
function onConnect (wsData) {
	$('.wrap_online').append('<div class="online_item" data-user="'+btoa(btoa(wsData.cliend_id))+'">'+'User _'+wsData.cliend_id+'</div>');
	cliend_id = wsData.cliend_id;
}
function onDisconnect (wsData) {
	$('.wrap_online').find('.online_item[data-user="'+btoa(btoa(wsData.cliend_id))+'"]').remove();
}
</script>
</html>