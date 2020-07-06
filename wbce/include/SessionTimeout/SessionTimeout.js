/**
 * Handle Session Timeout and display remaining session time
 */
document.cookie = 'WBCELastConnectJS='+Math.round(new Date().getTime() / 1000).toString()+'; expires=0; SameSite=Lax; path=/';

// Wait until DOM is loaded
document.addEventListener("DOMContentLoaded", function(e) { 

	let ttl = SESSION_TIMEOUT,
		countdownEl = document.getElementById('countdown');

	if (countdownEl) {
				
		countdownEl.innerHTML = '0:00:00';
		
		function timer() { 

			let days = Math.floor(ttl/24/60/60),
				hoursLeft = Math.floor((ttl) - (days*86400)),
				hours = Math.floor(hoursLeft/3600),
				minutesLeft = Math.floor((hoursLeft) - (hours*3600)),
				minutes = Math.floor(minutesLeft/60),
				seconds = Math.floor(ttl % 60);
			
			if (seconds < 10) {
				seconds = '0' + seconds; 
			}
			if (minutes < 10) {
				minutes = '0' + minutes; 
			}
			
			countdownEl.innerHTML = hours + ':' + minutes + ':' + seconds;
			
			if (ttl == 0) {
				clearInterval(timerInterval);
				countdownEl.innerHTML = 'Completed';
			} else {
				ttl--;
			}
			
			return timer;
		}
		
		let timerInterval = setInterval(timer(), 1000);
	}
});
