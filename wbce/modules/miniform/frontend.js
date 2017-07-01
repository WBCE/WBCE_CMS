if (window.jQuery) {  
	$(function() {

		var topOffset = 80;  // set this in pixels for a possible navbar offset

		$.fn.isInViewport = function(){
			var win = $(window);
			var viewport = {
				top : win.scrollTop(),
				left : win.scrollLeft()
			};
			viewport.right = viewport.left + win.width();
			viewport.bottom = viewport.top + win.height();
			var bounds = this.offset();
			bounds.right = bounds.left + this.outerWidth();
			bounds.bottom = bounds.top + this.outerHeight();
			return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
		};
		
		
		function init_ajaxform() {
			$('.miniform_ajax').each(function( index ) {
				var $container = $(this);
				var $form = $container.find('form');
				$form.off();
				$form.on('submit',$form,function(event){
					event.preventDefault(); 
					var data = new FormData($form[0]);
					$.ajax({
					    type: "POST",
						data: data,
						processData: false,
						contentType: false,
						beforeSend: function(){ $('.minispinner').fadeIn(10); },
						complete: function(){ $('.minispinner').fadeOut(500); },
						cache: false
					}).done(function(data) {
						$container.html(data);
						$message = $container.find('.error, .ok');
						if( $message && $message.isInViewport() == false ) {
							$('html,body').animate({
								scrollTop: $message.offset().top - topOffset
							}, 500);
						}
						init_ajaxform();
					});
				});
			});
		}
		init_ajaxform();
	});
}
