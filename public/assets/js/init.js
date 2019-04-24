jQuery(function($){

		// file witch will connect with ajax

	var processFile = "assets/inc/ajax.inc.php",

		// manipulation function for modal window

	 fx = {

		//return modal window if it exist
		//in another case create new

		"initModal" : function(){

			//if it have no needfull elements
			//lenght = 0!!

			if($(".modal-window").length==0)
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
		//document.getElementById("modal-window");
		console.log($(this).text());

		//take attribute from string "href"

		var data = $(this)
					.attr("href")
					.replace(/.+?\?(.*)$/,"$1");
		//check modal-window, hose one or create new

		var modal = fx.initModal();
		//console.log(modal);
		console.log('data', data);
		//dowload information about event from DB

		$.ajax({
			url: processFile,
			type: "POST",
			data: "action=event_view&" + data,
			success: function(data){
				//show information about event
				console.log('success data', data);
				modal.append(data);
			},
			error: function(msg) {
				modal.append(msg);
			}
		});
		
		
		//output request string

		// console.log(data);
	});

});