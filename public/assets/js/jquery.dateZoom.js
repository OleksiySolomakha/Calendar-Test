(function($){

	//Make bigger text when you point to date

	$.fn.dateZoom = function(options)
	{
		// redefine values with were edit by users in param

		var opts = $.extend($.fn.dateZoom.defaults, options);

		//organize cicle for all chosen elements
		//and return modife jQuery object for
		//support possibility of calling in chain	

		return this.each(function(){

			//save default text size

			var originalsize = $(this).css("font-size");

			//pin function to event 'hover'. firs off them
			//start works when you point to it, other starts 
			//when you stops point to it

			$(this).hover(function(){

					$.fn.dateZoom.zoom(this, opts.fontsize, opts);
				},

				function(){

					$.fn.dateZoom.zoom(this, originalsize, opts);
				});	
 
			});
		
	};

	$.fn.dateZoom.defaults = {
		
		"fontsize" : "130%",

		"easing" : "swing",

		"duration" : "600",

		"callback" : "null"
	};

	//helpfull function that can be use by user

	$.fn.dateZoom.zoom = function(element, size, opts)
	{

		$(element).animate({

			"font-size" : size

			},{

			"duration" : opts.duration,

			"easing" : opts.easing,

			"complete" : opts.callback

		})

		.dequeue() //cancel animation jumps

		.clearQueue(); //make only one animation
	};
})(jQuery);