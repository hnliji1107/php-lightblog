;(function(undefined) {
	var applicationCache = this.applicationCache;

	addEventListener('load', function(e) {
		// Check if a new cache is available on page load.
		applicationCache.addEventListener('updateready', function(e) {
	    	if (applicationCache.status === applicationCache.UPDATEREADY) {
		      	// Browser downloaded a new app cache.
		      	// Swap it in and reload the page to get the new hotness.
		      	applicationCache.swapCache();
		      	
		      	if (confirm('应用程序发现新版本，是否更新?')) {
		        	window.location.reload();
		      	}
		    }
		    else {
		      	// Manifest didn't changed. Nothing new to server.
		    }
		}, false);

		//output now time
		output();
	}, false);


	function output() {
		setTimeout(function() {    
		    document.getElementById('time').innerText = new Date();    
		}, 1000);
	}
}).call(this);