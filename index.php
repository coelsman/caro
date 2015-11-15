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
		_myReady = _oppReady = false,
		opp_client_id,
		client_id, _table, _isOnTable = false,
		_typeShape;

// generator.create();
// $('#tiktaktoe .tiktok_item').addClass('x');
$('#tiktaktoe').on('click', '.tiktok_item', function () {
	var col = parseInt($(this).attr('col')),
			row = parseInt($(this).attr('row'));

	wsHandle.send(JSON.stringify({
		type: 'mark',
		data: {
			col: col,
			row: row,
			table: _table,
			type: _typeShape
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

$('#ready').on('click', '.btn_play:not(.disable)', function () {
	wsHandle.send(JSON.stringify({
		type: 'ready',
		data: {
			table: _table,
			client_id: client_id
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
		case 'mark': 
			onMark(data.data);
			break;
		case 'join':
			onJoin(data.data);
			break;
		case 'ready':
			onReady(data.data);
			break;
		case 'start':
			onStart(data.data);
			break;
	}
}

function onMark (wsData) {
	if (wsData.table == _table)
		$('#tiktaktoe').find('.tiktok_item[row="'+wsData.row+'"][col="'+wsData.col+'"]').addClass('m'+wsData.type);
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
	console.info('On Join - Get Table Infor');
	getTableInfor(function (js) {
		var pos, row, isOppJoined = false;

		if (_isOnTable == true && wsData.table == _table) {
			for (var i in js) {
				row = $('.user_game').find('.user_item[user-nth="'+i+'"]');
				if (js[i] == null || typeof js[i] == 'undefined')
					row.find('.name').html('Waiting player ...');
				else {
					if (js[i] == client_id)
						row.attr('cliend-id', btoa(btoa(js[i]))).find('.name').html('(You) User _'+client_id).css('font-weight', 'bold');
					else {
						isOppJoined = true;
						row.attr('cliend-id', btoa(btoa(js[i]))).find('.name').html('User _'+js[i]);
					}
				}
			}
			$('.wrap_table_no').html('Table No.'+wsData.table);

			if (isOppJoined)
				$('#ready .btn_play').removeClass('disable');
		} else {
			for (var i in js) {
				row = $('.wrap_table').find('.r_table[data-table="'+btoa(btoa(wsData.table))+'"]');
				if (js[i] == parseInt(js[i]))
					row.find('.ico[data-pos="'+i+'"]').addClass('busy').html('U_'+js[i]).css('background-color', '#0f0');
			}
		}
	}, wsData.table);
}
function onReady (wsData) {
	if (_isOnTable == true && wsData.table == _table) {
		if (wsData.client_id == client_id) {
			_myReady = true;
		} else {
			opp_client_id = wsData.client_id;
			_oppReady = true
		}

		$('.user_game').find('.user_item[cliend-id="'+btoa(btoa(wsData.client_id))+'"]').css('color', '#00f');

		if (_myReady && _oppReady) {
			var data = {};

			generator.create();
			$('#ready').addClass('hide');
			$('#tiktaktoe').removeClass('hide');

			data[opp_client_id] = randomShape();
			data[client_id] = (data[opp_client_id] == 'x') ? 'o' : 'x';
			data.table = _table;

			wsHandle.send(JSON.stringify({
				type: 'start',
				data: data
			}));
		}
	}
}
function onStart (wsData) {
	if (_isOnTable == true && wsData.table == _table) {
		_typeShape = wsData[client_id];

		console.info('Start Game');
		$('#tiktaktoe .tiktok_item').addClass(_typeShape);
	}
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
			'<div class="btn_play disable">Ready</div>'+
		'</div>'+
		'<div class="wrap_btn">'+
			'<div class="btn_quit">Quit Game!</div>'+
		'</div>'+
	'</div>')
}

function randomShape () {
	var r = ['x', 'o'];
	return r[Math.floor(2 * Math.random())];
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