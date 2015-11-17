<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/tiktaktoe.css">

	<title>Confirm Nickname</title>
	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
</head>
<body id="confirm">
	<div class="wrap_confirm">
		<input type="text" id="txt-name" value="" placeholder="Enter your Nickname">
		<input type="button" id="btn-confirm" value="Confirm">
	</div>
</body>
<script type="text/javascript">
$('#btn-confirm').on('click', function () {
	saveNickname($('#txt-name').val());
});

$('#txt-name').on('keypress', function (e) {
	var ev = e.keyCode || e.which;
	if (ev == 13) {
		saveNickname($(this).val());
	}
});

function saveNickname (name) {
	localStorage.setItem('caro_nickname', btoa(btoa(name)));
	window.location.href = 'index.php';
}
</script>
</html>