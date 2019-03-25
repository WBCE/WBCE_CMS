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
		
		$(":submit").click(function() { 
			$('#ucomp').val(''); 
			$('#uname').val(''); 
			$('#umail').val(''); 
		});
		
		function init_ajaxform() {
			var initiator = '';
			$(":submit").click(function() { initiator = this;  });
			$('.miniform_ajax').each(function( index ) {
				var $container = $(this);
				var $form = $container.find('form');
				$form.off();
				$form.on('submit',$form,function(event){
					event.preventDefault(); 
					/* fix for bug in Safari with empty file-upload fields */
					$form.find( ':input[type="file"]' ).each( function( index, el ) {
						if (el.value == '') {
							$(this).prop('disabled', true);
							console.log('/* safari bugfix */ disabled empty file upload field: '+el.name);
						}
					});	
					var data = new FormData($form[0]);
					data.append(initiator.name,initiator.value);
					$.ajax({
					    type: "POST",
						data: data,
						processData: false,
						contentType: false,
						beforeSend: function(){ $('.minispinner').fadeIn(10); },
						cache: false,
						headers: { "cache-control": "no-cache" }
					})
					.done(function(data) {
						$container.html(data);
						$message = $container.find('.error, .ok');
						//console.log($message); 
						if($message.length && $message.isInViewport() == false ) {
							$('html,body').animate({
								scrollTop: $message.offset().top - topOffset
							}, 500);
						}
					})
					.always(function () { 
						$('.minispinner').fadeOut(500);
						init_ajaxform();
					});
				});
			});
		}
		init_ajaxform();
	});
}
