var http = require('http');
var ts = {};
var server = require('net').createServer(function(socket){
	
	console.log('client connected');
	
	socket.on('end', function() {
		console.log('client disconnected');
	});
	
	socket.on('data', function(data) {
		data = data.toString();
		console.log('request: '+data);
		
		if (typeof ts[data] != 'undefined')
		{
			console.log('TS IP FROM CACHE: '+ts[data]);
			socket.end(ts[data]);
			return;
		}
		
		http.get('http://support.wot.pw/api/getTsIp?url='+data, function(res) {
			if (typeof res.headers.tsip != 'undefined' && res.headers.tsip != '')
			{
				ts[data] = res.headers.tsip;
				console.log('TS IP: '+res.headers.tsip);
				socket.end(res.headers.tsip);
			}
			else
				socket.end();
		}).on('error', function(e) {
			socket.end();
		});
	});
	
	socket.on('error', function() { console.log("ERRROR");});
		
});

server.listen(41144, function() {
	console.log('server started');
});
