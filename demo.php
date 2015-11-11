<html>
	
	<script type="text/javascript">
var wsUrl = 'ws://127.0.0.2:9300';
var wsHandle = new WebSocket(wsUrl);

console.log('send data');
setTimeout(function () {
	wsHandle.send(JSON.stringify({
		username: 'Thanh Dao',
		position: '2'
	}));
}, 1000);


	</script>
</html>