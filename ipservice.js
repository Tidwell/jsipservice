(function(undefined) {
	if (window.ipService) {
		warn('Overwriting existing ipService variable');
	}

	// counts the number of callbacks initiated, used for generating unique callback names
	var cbCount = 0;
	// used to store user callbacks so they are accessable from global
	var callbacks = {
		'default': function(ip){ log(ip); }
	};

	/*
		Generator used to return a function to call the users callback and delete the
		callback off of the temporary storage object
	*/
	function CB(callback,cbName) {
		return function(ip) {
			callback(ip);
			delete callbacks[cbName];
		};
	}

	/*
		Public
		@requires function callback - to be called with the ip address of the client
	*/
	function get(callback) {
		var cbName = 'default';
		if (callback) {
			cbCount++;
			cbName= 'cb'+cbCount;
			callbacks[cbName] = new CB(callback,cbName);
		}
		script = document.createElement('script');
		script.type = 'text/javascript';
		script.src = 'http://www.jsipservice.com/REST?callback=ipService.callbacks.'+cbName;
		document.getElementsByTagName('head')[0].appendChild(script);
	}

	window.ipService = {
		get: get,
		callbacks: callbacks
	};

	function warn(txt) {
		if (console && console.warn) {
			console.warn(txt);
		}
	}
	function log(txt) {
		if (console && console.log) {
			console.log(txt);
		}
	}
}());