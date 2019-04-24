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
							//.hide()
							.addClass("modal-window")
							.appendTo("body");
					}
					else
					{
						//return modal window if it exist in DOM

						return $(".modal-window");
					}
				},

				//slowly modal-window apear

				"boxin":function(data,modal){

					$("<div>")
						.hide()
						.addClass("modal-overlay")
						.click(function(event) {
							/* delete event */

							fx.boxout(event);
						})

						appendTo("body");

						//download data in modal-window

						modal
							.hide()
							.append(data)
							.appendTo("body");

						//make slowly modal-wondow apear

						$(".modal-window,.modal-overlay")

							.fadeIn("slow");				
							
				},

				//slowly window disappeared

				"boxout":function(event){

						if(event!=undefined){

							event.preventDefault();
						}
						//remove class Active from all links

						$("a").removeClass('active');

						//make slowly window disappear
						//then remove it from DOM
						$(".modal-window,.modal-overlay")
							.fadeOut("slow", function() {
								$(this).remove();
							});

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
		//console.log('data', data);
		

		//create exit buttom for modal-window

		$("<a>")
			.attr('href', '#')
			.addClass("modal-close-btn")
			.html("&times;")
			.click(function(event){
				
				//delete modal window

				fx.boxout(event);


				//end action 

				//event.preventDefault();
				//delete modal-window
				// $(".modal-window")
				// 	.remove();
			})
		.appendTo(	modal);

		//dowload information about event from DB

		$.ajax({
			url: processFile,
			type: "POST",
			data: "action=event_view&" + data,
			success: function(data){

				fx.boxin(data,modal);
				//show information about event
				// console.log('success data', data);
				// modal.append(data);
			},
			error: function(msg) {
				modal.append(msg);
			}
		});
		
		
		//output request string

		// console.log(data);
	});

});