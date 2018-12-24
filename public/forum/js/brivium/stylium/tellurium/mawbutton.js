(function($){

	$.fn.mawbutton = function (options) {
		var settings = $.extend({
			effect : "ripple",
			scale : 3,
			speed : 300,					// ms
			transitionEnd : function(){}	// callback when transition ends.
		}, options);

		return this.each(function() {
			var $this = $(this);
			//var supportEvent = ('ontouchstart' in window ) ? 'touchstart':'click';

			$this.addClass('mawbutton')
			.on('click', function(e) {		//bind touch/click event
				
				$this.append('<div class="mawbutton-'+settings.effect+'" style="background-color: '+$(this).css('color')+'"></div>');
				
				// Fetch click position and size
				var posX = $this.offset().left,
					posY = $this.offset().top;

				var w = $this.outerWidth(),
					h = $this.outerHeight();
				var targetX= e.pageX - posX;
				var targetY= e.pageY - posY;

				//Fix target position
				if(!targetX || !targetY){
					 targetX = e.originalEvent.touches[0].pageX;
					 targetY = e.originalEvent.touches[0].pageY;
				}

				var ratio = settings.scale / 2;    							

				//Animate Start
				$effectElem = $this.children(':last');
				$effectElem.addClass("mawbutton-stop")
							.css({
								"top" : targetY,
								"left" : targetX,
								"width" : w * settings.scale,
								"height" : w * settings.scale,
								"margin-left" : -w * ratio,
								"margin-top" : -w * ratio,
								"transition-duration" : settings.speed+"ms",
								"-webkit-transition-duration" : settings.speed+"ms",
								"-moz-transition-duration" : settings.speed+"ms",
								"-o-transition-duration" : settings.speed+"ms"
							});
				$effectElem.removeClass("mawbutton-stop");

				//Animate End
				setTimeout(function(){
					$effectElem.addClass("mawbutton-"+settings.effect+"-out").css({
						"transition-duration" : settings.speed+"ms",
						"-webkit-transition-duration" : settings.speed+"ms",
						"-moz-transition-duration" : settings.speed+"ms",
						"-o-transition-duration" : settings.speed+"ms"
					});
					setTimeout(function(){
						$this.find(".mawbutton-"+settings.effect).first().remove();
						settings.transitionEnd.call(this);
					},settings.speed);
				}, settings.speed);
			});
		});
	}
}(jQuery));
