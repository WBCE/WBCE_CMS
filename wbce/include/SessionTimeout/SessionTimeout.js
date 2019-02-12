/**
 * Handle Session Timeout and display remaining session time
 */

var seconds = new Date().getTime() / 1000;
seconds = Math.round(seconds);
document.cookie = 'WBCELastConnectJS='+seconds.toString()+'; expires=0; path=/';
var upgradeTime = SESSION_TIMEOUT;

var seconds = upgradeTime;
function timer() {
	var days        = Math.floor(seconds/24/60/60);
	var hoursLeft   = Math.floor((seconds) - (days*86400));
	var hours       = Math.floor(hoursLeft/3600);
	var minutesLeft = Math.floor((hoursLeft) - (hours*3600));
	var minutes     = Math.floor(minutesLeft/60);
	var remainingSeconds = seconds % 60;
	if (remainingSeconds < 10) {
		remainingSeconds = '0' + remainingSeconds; 
	}
	document.getElementById('countdown').innerHTML = hours + ':' + minutes + ':' + remainingSeconds;
	if (seconds == 0) {
		clearInterval(countdownTimer);
		document.getElementById('countdown').innerHTML = 'Completed';
	} else {
		seconds--;
	}
}
var countdownTimer = setInterval('timer()', 1000);