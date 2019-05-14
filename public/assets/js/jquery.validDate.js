//check correct data in string date and time
//YYYY-MM-DD HH-MM-SS

function validDate(date)
{
	//find patter of regular expressions 
	// for check corect form of date

	var pattern = /^(\d{4}(-\d{2}){2} (\d{2})(:\d{2}){2})$/;

	// return true if it is correct , false if - wrong

	return  date.match(pattern)!=null;
}

//create JQuery function

(function($){

//update jQuery object for checking data string validation

$.validDate = function(date,options){

		var defaults = {

			"pattern" : /^(\d{4}(-\d{2}){2} (\d{2})(:\d{2}){2})$/

			},
			//add users parametrs

			opts = $.extend(defaults, options);

			//if find match, return true, else false

			return date.match(opts.pattern)!=null;
			


	};
})(jQuery);