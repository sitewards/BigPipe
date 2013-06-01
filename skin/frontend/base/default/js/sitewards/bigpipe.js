var bigpipeInterval = window.setInterval(
	function () {
		var bigpipe = document.getElementById('bigpipe');
		if (bigpipe) {
			var bigpipes = bigpipe.getElementsByClassName('bigpipe');
			if (bigpipes.length > 0) {
				for (var x = 0; x < bigpipes.length; x++) {
					document.getElementById(bigpipes[x].getAttribute('data-target')).outerHTML = bigpipes[x].innerHTML;
					bigpipes[x].parentNode.removeChild(bigpipes[x]);
				};
			}
			/**
			 * got all bigpipe blocks, we can remove temporary div and clear this interval
			 */
			if (document.getElementById('bigpipe-finished').length > 0) {
				document.body.removeChild(bigpipe);
				window.clearInterval(bigpipeInterval);
			}
		}
	},
	100
)