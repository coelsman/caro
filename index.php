<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/tiktaktoe.css">

	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
</head>
<body>
	<div class="wrap_table">
		<?php for ($i=0; $i < 21; $i++) { ?>
			<div class="col-4">
				<div class="r_table" data-table="<?php echo base64_encode(base64_encode($i+1)); ?>">
					<div class="ico ico_1" data-pos="1"></div>
					<div class="ico ico_2" data-pos="2"></div>
					<div class="mark"><?php echo $i+1; ?></div>
				</div>
			</div>
		<?php } ?>
	</div>
	<div class="wrap_online">
		
	</div>
	<div class="clearfix"></div>

	<!-- ______________________________________________________ -->
	<div class="wrap_table_no"></div>
	<div id="ready" class="hide">
		
	</div>
	<div id="tiktaktoe" class="hide"></div>
	<div class="user_game hide">
		<div class="user_item" user-nth="1">
			<div class="wrap_icon_name">
				<div class="icon"></div>
				<div class="name"></div>
			</div>
			<div class="status"></div>
		</div>
		<div class="user_item" user-nth="2">
			<div class="wrap_icon_name">
				<div class="icon"></div>
				<div class="name"></div>
			</div>
			<div class="status"></div>
		</div>
	</div>
	<div class="clearfix"></div>
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
		client_id, _table, _isOnTable = false;

$('#tiktaktoe .tiktok_item').addClass('x');

$('#tiktaktoe').on('click', '.tiktok_item', function () {
	var col = parseInt($(this).attr('col')),
			row = parseInt($(this).attr('row'));

	wsHandle.send(JSON.stringify({
		type: 'game',
		data: {
			col: col,
			row: row,
			table: _table
		}
	}));
});

$('.wrap_table').on('click', '.ico:not(.busy)', function () {
	var row = $(this),
			pos = row.attr('data-pos'),
			data = {
				pos: parseInt(pos),
				table: parseInt(atob(atob(row.parent().attr('data-table'))))
			};

	wsHandle.send(JSON.stringify({
		type: 'join',
		data: data
	}));

	_table = data.table;
	_isOnTable = true;

	$('.wrap_table').addClass('hide');
	$('.wrap_online').addClass('hide');
	// $('#tiktaktoe').removeClass('hide');
	$('.user_game').removeClass('hide');
	readyScreen();
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
		case 'join':
			onJoin(data.data);
			break;
	}
}

function onGame (wsData) {
	if (wsData.table == _table)
		$('#tiktaktoe').find('.tiktok_item[row="'+wsData.row+'"][col="'+wsData.col+'"]').html('x');
}
function onConnect (wsData) {
	$('.wrap_online').append('<div class="online_item" data-user="'+btoa(btoa(wsData.client_id))+'">'+'User _'+wsData.client_id+'</div>');
	if (typeof client_id == 'undefined')
		client_id = wsData.client_id;

	if (wsData.listOnline != null) {
		wsData.listOnline.forEach(function (val, idx) {
			if (val != wsData.client_id)
				$('.wrap_online').append('<div class="online_item" data-user="'+btoa(btoa(val))+'">'+'User _'+val+'</div>');
		});
	}

	updateTableList();
}
function onDisconnect (wsData) {
	$('.wrap_online').find('.online_item[data-user="'+btoa(btoa(wsData.client_id))+'"]').remove();
	updateTableList()
}
function onJoin (wsData) {
	getTableInfor(function (js) {
		var pos, row;

		if (_isOnTable == true && wsData.table == _table) {
			for (var i in js) {
				row = $('.user_game').find('.user_item[user-nth="'+i+'"]');
				if (js[i] == null || typeof js[i] == 'undefined')
					row.find('.name').html('Waiting player ...');
				else {
					if (js[i] == client_id)
						row.find('.name').html('(You) User _'+client_id).css('font-weight', 'bold');
					else
						row.find('.name').html('User _'+js[i]);
				}
			}
			$('.wrap_table_no').html('Table No.'+wsData.table);
		} else {
			for (var i in js) {
				row = $('.wrap_table').find('.r_table[data-table="'+btoa(btoa(wsData.table))+'"]');
				if (js[i] == parseInt(js[i]))
					row.find('.ico[data-pos="'+i+'"]').addClass('busy').html('U_'+js[i]).css('background-color', '#0f0');
			}
		}
	}, wsData.table);
}

function updateTableList () {
	var row;
	getTableInfor(function (js) {
		for (var i in js) {
			row = $('.wrap_table').find('.r_table[data-table="'+btoa(btoa(i))+'"]')
			for (var j in js[i]) {
				if (js[i][j] == parseInt(js[i][j]))
					row.find('.ico[data-pos="'+j+'"]').addClass('busy').html('U_'+js[i][j]).css('background-color', '#0f0');
				else
					row.find('.ico[data-pos="'+j+'"]').removeClass('busy').html('').css('background-color', '');
			}
		}
	});
}

function readyScreen () {
	console.info('Ready Screen');
	var row = $('#ready').removeClass('hide');
	row.append('<div class="wrap_ready_button">'+
		'<div class="wrap_btn">'+
			'<div class="btn_play">Ready</div>'+
		'</div>'+
		'<div class="wrap_btn">'+
			'<div class="btn_quit">Quit Game!</div>'+
		'</div>'+
	'</div>')
}

/*******************************************
*                                          *
*                AJAX EVENTS               *
*                                          *
********************************************/
function getTableInfor (callback, table) {
	if (typeof table != 'undefined')
		table = '?table='+table;
	else
		table = '';
	$.ajax({
		url: '/caro/server/action/getTable.php'+table,
		data: 'GET',
		dataType: 'json',
		success: function (js) {
			callback(js);
		}
	});
}
</script>
</html>