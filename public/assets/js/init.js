jQuery(function($){

		// manipulation function for modal window

	var fx = {

		//return modal window if it exist
		//in another case create new

		"initModal" : function(){

			//if it have no needfull elements
			//lenght = 0!!

			if($(".modal-window").lenght==0)
			{
				//create div element,
				//add class and it to descriptor

				return $("<div>")
					.addClass("modal-window")
					.appendTo("body");
			}
			else
			{
				//return modal window if it exist in DOM

				return $(".modal-window");
			}
		}
	};

	//catch event in modal window

	$("li>a").live("click", function(event) {

		// cancel download view.php file when we click on it

		event.preventDefault();

		//edit class "active" to the link

		$(this).addClass("active");

		//take attribute from string "href"

		var data = $(this)
					.attr("href")
					.replace(/.+?\?(.*)$/,"$1");

		//check modal-window, hose one or create new

		modal = fx.initModal();

		//output request string

		console.log(data);
	});

});