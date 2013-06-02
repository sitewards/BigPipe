/**
 * example for observer
 * @param element
 */
/*function bigpipeObserver(element) {
	alert(element.innerHTML);
}*/

function function_exists(functionname) {
	return (typeof window[functionname] === 'function');
}

var bigpipeInterval = window.setInterval(
	function () {
		var bigpipe = document.getElementById('bigpipe');
		if (bigpipe) {
			var bigpipes = bigpipe.getElementsByClassName('bigpipe');
			if (bigpipes.length > 0) {
				for (var x = 0; x < bigpipes.length; x++) {
					var targetId = bigpipes[x].getAttribute('data-target');
					var targetElement = document.getElementById(targetId);
					targetElement.outerHTML = bigpipes[x].innerHTML;
					if (function_exists('bigpipeObserver')) {
						bigpipeObserver(bigpipes[x]);
					}
					bigpipes[x].parentNode.removeChild(bigpipes[x]);
				};
			}
			/**
			 * got all bigpipe blocks, we can remove temporary div and clear this interval
			 */
			if (document.getElementById('bigpipe-finished') && document.getElementById('bigpipe-finished').length > 0) {
				document.body.removeChild(bigpipe);
				window.clearInterval(bigpipeInterval);
			}
		}
	},
	100
)