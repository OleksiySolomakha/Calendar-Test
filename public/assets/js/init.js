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
							.hide()
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

						.appendTo("body");

						//download data in modal-window

						modal
							.hide()
							.append(data)
							.appendTo("body");

						//make slowly modal-wondow apear

						$(".modal-window,.modal-overlay")

							.fadeIn("slow");				
							
				},

				// //slowly window disappeared

				"boxout":function(event){

						if(event!=undefined)
						{
							event.preventDefault();
						}
						//remove class Active from all links

						$("a").removeClass("active");

						//make slowly window disappear
						//then remove it from DOM
						$(".modal-window,.modal-overlay")
							.fadeOut("slow", function() {
								$(this).remove();
								console.log("window close");
							});

						

					},

					//add new event after save

				"addevent":function(data,formData){

						//make obj from string

						var entry = fx.deserialize(formData),

						//create obj "date" for this month

						cal = new Date(NaN),

						//create obj "date" for new event 

						event = new Date(),

						// take from 'id, h2' calendar month 
 							
 						cdata = $("h2").attr("id").split('-'),

 						// take day , month and year from event

 						date = entry.event_start.split(' ')[0],

 						//break event data by parts

 						edata = date.split('-');
 						console.log(event);
 						console.log(cdata);
 						console.log(date);

 						//make date for calendar obj "date"

 						 cal.setFullYear(cdata[1], cdata[2], 1);
 						 console.log(cal.setFullYear(cdata[1], cdata[2], 1));

 						//make date for event obj "date"  

 						 event.setFullYear(edata[0], edata[1], edata[2]);
 						 console.log(event.setFullYear(edata[0], edata[1], edata[2]));

 						// set correct time zone

 						event.setMinutes(event.getTimezoneOffset());

 				

 							//add event to calendar if year and month match

 							//if(true)
 							if(cal.getFullYear()==event.getFullYear()
 								&& cal.getMonth()==event.getMonth())
 							{

 								var day = String(event.getDate());
 						
 								//var day = edata[2]
 								 //console.log(event);

 								 console.log(day);

 								 // find timezone (location)
								console.log(Intl.DateTimeFormat().resolvedOptions().timeZone)
								//check day

 								var day = day.length==1 ? "0" + day : day;
 								console.log(day);
 								// if(day.lenght==1){

 								// 	day="0"+day;

 								// day+=1; 
 								// exit($day);}

 								$("<a>")
 									.hide()
 									.attr("href","view.php?event_id="+data)
 									.text(entry.event_title)
 									.insertAfter($("strong:contains("+day+")"))
 									.delay(1500)
 									.fadeIn("slow");
 							}
					},	

					// remove event after delete

					"removeevent":function()
					{
						//exclude every event with class active
						$(".active")
							.fadeOut("slow", function() {
									$(this).remove();
							});
							console.log("Event delete");
					},

					"deserialize":function(str){

							//drop all couples name-value for two part

						var data = str.split("&");

							//name variables for use them in cycle

						var	pairs = [], entry = {}, key, val;

							//do cycle with all couples

						for(x in data)
						{
							//show  couples in massivee view

							pairs = data[x].split("=");

							//first element - name

							key = pairs[0];

							//second element - value

							val = pairs[1];

							//save all values like object property

							entry[key] = fx.urldecode(val);

						} 

						return entry;
					},

					//decoding string value

					"urldecode":function(str){

						//made space from +

						var converted = str.replace(/\+/g, ' ');

						//make reverse enother obj

						return decodeURIComponent(converted);
					}
			};

	//make dafault font-size for method dateZoom

	$.fn.dateZoom.defaults.fontsize = "13px";

	//catch event in modal window
	//add zoom effect

	$("li a")
		.dateZoom()
		.live("click",function(event)

	//$("li>a").live("click", function(event)
	 {

		// cancel download view.php file when we click on it

		event.preventDefault();

		//edit class "active" to the link

		$(this).addClass("active");	
		//document.getElementById("modal-window");
		//console.log($(this).text());

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

				// event.preventDefault();
				// delete modal-window
				// $(".modal-window")
				// 	.remove();
			})

		.appendTo(modal);

		//dowload information about event from DB

		$.ajax({
			url: processFile,
			type: "POST",
			// data: "action=event_view&" + data,
			data: "action=event_view&"+data,
			success: function(data){

				fx.boxin(data, modal);
				//show information about event
				 console.log('success data', data);
				// modal.append(data);
			},
			error: function(msg) {
				modal.append(msg);
			}
		});
		
		
		//output request string

		// console.log(data);
	});

	
	$(".admin-options form,.admin").live("click",function(event)
		{

			/* stop send form */

			event.preventDefault();

			//make attribute 'action' for send form

			var action = $(event.target).attr("name")||"edit_event";

			//save element with name "event_id"

			 id = $(event.target)
						.siblings("input[name=event_id]")
						.val();

			//create parametr for identification if it instal

			 id = (id!=undefined) ? "&event_id="+id : "";

			// var action = "edit_event";

			//var action ="delete_event";

			//dowload form for edit event add show it 

			$.ajax({
				url: processFile,
				type: "POST",
				// data: "action=event_view&" + data,
				data: "action="+action+id,
				success: function(data){

					//hide form

					var form = $(data).hide(),

					//make sure that modal-window exists

					 modal = fx.initModal()
							.children(":not(.modal-close-btn)")
							.remove()
							.end();

					//call boxin function with her parametrs

					 fx.boxin(null,modal);

					//download form in window, moke slow appearens inf

					form
						.appendTo(modal)
						.addClass("edit-form")
						.fadeIn("slow");

					//show information about event

					console.log('success data', action);
					// modal.append(data);
				},
				error: function(msg)
				{
					alert(msg);
				}
			});

				// show message about working status 

				console.log("click was made on edit new event buttom!");

		});

		//Edit events without reload page

		$(".edit-form input[type=submit]").live("click", function(event) {
			/* to prevent default action for form*/

			event.preventDefault();

			//serialize data in form for use it with ajax function

			var formData = $(this).parents("form").serialize();
			
			//check!!!!

			console.log("Start send data in db", formData);
			
			// save "submit" buttom means
			
			var submitVal = $(this).val();

			//save string about event start 

			var start = $(this).siblings("[name=event_start]").val();

			//save string about event end

			var end = $(this).siblings("[name=event_end]").val();

			//check does event must be removed

			var remove = false;

			//if it is delete form add action

			if ($(this).attr("name")=="confirm_delete")
			{
				// add needfull information into string

				formData += "&action=confirm_delete"+"&confirm_delete="+submitVal;

				console.log("Confirm delete, go to confirm delete form");

				//if event delete , made mark for removing it from calendar

				if (submitVal=="Yes I agree!")
				{
					remove = true;

					console.log("Event was delete");
				}

			}

			//if event edit/create check correct data

			if ($(this).siblings("[name = action]").val()=="event_edit")
			{
				if( !validDate(start) || !validDate(end) )
				{
					alert("Date must be like (YYYY-MM-DD HH:MM:SS)");
					return false;
				}
			}

			//show message for check in console

			//console.log("form is working");

			//send data to process file

			$.ajax({
				url: processFile,
				type: "POST",
				data: formData,
				success:function(data){

					if(remove===true)
					{
						fx.removeevent();
					}

					fx.boxout();

					//add event in calendar if it new

					 if ($("[name=event_id]").val().length==0&&remove===false)
					{
						
					// modal-window slowly desappear 

						fx.addevent(data,formData);
					}
					
					
					//slowly disapearens modal-windo


					//write message into console

					//console.log("Save event!!!", data );
					
				},
				error:function(msg) {

					/*alert*/
					
					alert(msg);

				}
			});
			
		});

		// make buttom Cancel in edit event forms 
		//functions like buttom Exit  for slowly
		//close and disappear modal-window and overlay

		$(".edit-form a:contains('Cancel')").live("click", function(event){

				fx.boxout(event);

				console.log("cancel action!!");

		}); 

});