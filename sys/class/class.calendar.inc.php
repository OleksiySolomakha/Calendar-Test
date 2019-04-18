<?php
	//Класс с поключением к БД, для оздания календаря и работай с ним
	/**
	 * Класс с поключением к БД, для оздания календаря и работай с ним
	 */
	include_once '../sys/class/class.db_connect.inc.php';



	class Calendar extends DB_Connect
	{
		private $_useDate; //данные для содания календаря гггг-мм-дд чч:мм:сс

		private $_m; //месяц который используют при создании календаря 

		private $_y; //год который используют при создании календаря

		private $_daysInMonth; // к-тво дней в текущем месяце	

		private $_startDay;	//индекс с интервалом от 0 до 6 ,
							// указывает день недели ,с которого начинается месяц 
			// сюда будут помещены свойтва и методы класса
		public function __construct($dbo = NULL, $useDate = NULL)
			// $dbo=NULL объект БД
			// $useDate дата выбраная для построения календаря
		{
			// Вызов конструктор родитеьского класса 
			//для проверки существование объекта БД
			parent::__construct($dbo);
		

			// colect and save information wich relate to month

			if (isset($useDate)) 
			{
				$this -> _useDate = $useDate;	
			}
			else
			{
				$this -> _useDate = date('Y-m-d H:i:s');
			}

			// transform in UNIX timestamp, then define month and year
			// wich will be used to create a calendar
			
			$ts = strtotime($this -> _useDate);
			$this -> _m = date('m', $ts);
			$this -> _y = date('y', $ts);

			//define how many days in month

			$this -> _daysInMonth = cal_days_in_month(
				CAL_GREGORIAN,
				$this -> _m,
				$this -> _y
			);

			//define, from what day of week month begin

			$ts = mktime(0, 0, 0, $this -> _m, 1, $this -> _y);
			$this -> _startDay = date('w', $ts);

		}

		//download information about events
		//@param int $id is optional parameter
		//use for filter results
		//@return array events massive from DB

		private function _loadEventData($id=NULL)
		{
			$sql = "SELECT `event_id`, `event_title`, `event_desc`, 
			`event_start`, `event_end` FROM `events`";
			if (!empty($id)) 
			{
				
				$sql .= " WHERE `event_id` =:id LIMIT 1 ";

				//print_r($sql); exit;
			} 

			else
			
				// in another case download all events in this month
			
			{

				//find first and last days in month

				$start_ts = mktime(0, 0, 0, $this->_m, 1,$this -> _y);
				$end_ts = mktime(23, 59, 59, $this->_m+1, 0	, $this -> _y);
				$start_date = date('Y-m-d H:i:s', $start_ts);
				$end_date = date('Y-m-d H:i:s', $end_ts);
 
				//filter needfull events for this month

				$sql .= " WHERE `event_start` BETWEEN '$start_date'
				 AND '$end_date' ORDER BY `event_start`";
				 
			}

			try

			{
				
				$stmt = $this->db->prepare($sql);
				
				 //connect parameter if id where transferred 

				 if (!empty($id)) 
				 {
				 	$stmt->bindParam(":id", $id, PDO::PARAM_INT);

				}

				$success = $stmt->execute();
				if($success) {
					$results = $stmt->fetchAll();
					$stmt->closeCursor();
	
					return $results;
				} else {
					print_r($stmt->errorInfo());
				}
				return [];
			}

			catch( Exception $e)
			{	

				die( $e -> getMessage());

			}

		}

		public function _buildCalendar()
		{

			$cal_month = date('F Y', strtotime($this -> _useDate));
			
			$weekdays = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 
								'Fri', 'Sat');

			// Header to HTML for calendar

			$html = "\n\t<h2>$cal_month</h2>";

			for ($d = 0, $lables = NULL; $d < 7; ++$d )
			{ 
				
				$lables .="\n\t\t<li>" . $weekdays[$d] . "</li>";

			}

			$html .= "\n\t <ul class=\"weekdays\">" . $lables . "\n\t</ul>";

			//download event data

			$events = $this -> _createEventObj();

			// calendar body

			$html .= "\n\t<ul>"; // new list 

			for ($i = 1, $C = 1, $t = date('j'), $m = date('m'), $y = date('y');
			 $C<= $this -> _daysInMonth; ++$i)
			{ 
				
				$class = $i <= $this -> _startDay ? "fill" :NULL;

				if ($C==$t && $m==$this -> _m && $y==$this -> _y ) 
				{

					$class = "today";

				}

				$ls = sprintf("\n\t\t<li class=\"%s\">", $class);
				
				$le = "\n\t\t</li>";

				if ($this -> _startDay<$i && $this -> _daysInMonth >= $C)
				{
					//form data about events

					$event_info = NULL; //clear the variable

					if (isset($events[$C]))
					{

						foreach ($events[$C] as $event)
						{

							$link = '<a href = "view.php?event_id='
							. $event -> id . '">' . $event -> title . '</a>';

							$event_info .= "\n\t\t\t$link";
						
						}
					
					}


					$date = sprintf("\n\t\t\t<strong>%02d</strong>", $C++);


				}

				else
				{

					$date = "&nbsp;";

				}
					// if is this day saturnday go to next string

				$wrap = $i!=0 && $i%7==0 ? "\n\t</ul>\n\t<ul>" : NULL; 

				// make groop from this elements

				$html .= $ls . $date . $event_info . $le . $wrap;

			}

			while ($i%7!=1)
			
			{
				$html.= "\n\t\t<li class =\"fill\">&nbsp;</li>"; ++$i;
			}

			// close list 

			$html .= "\n\t</ul>\n\n";

			//show admin options 

			$admin = $this->_adminGeneralOptions();

			return $html.$admin;



		}


		// show information about event

		public function _displayEvent($id)
		{

			//make sure that we get id

			if (empty($id))
			{
				return NULL;
			}

			// make sure that id is integer

			$id = preg_replace('/[^0-9]/','',$id);

			//download dat about event from BD

			$event = $this -> _loadEventById($id);

			//generate srtings for start and end data time

			$ts = strtotime($event -> start);

			$date = date('F d, Y', $ts);

			$start = date('g:ia', $ts);

			$end = date('G:ia', strtotime($event ->end));

			//if user has enter , show admin options

			$admin = $this->_adminEntryOptions($id);

			// generate and return markup

			return "<h2>$event->title</h2>"
				. "\n\t<p class=\"dates\">$date, $start&mdash;$end</p>"
				. "\n\t<p>$event->description</p>$admin";




		}

		//Create events generation form

		public function displayForm()
		{

			// connect ID

			if (isset($_POST['event_id']))
			{

				$id = (int) $_POST['event_id'];
				
			}
			else
			{

				$id=NULL;

			}

			$submit = "Create  Event Trulala";

			if (!empty($id)) 
			{

				$event = $this->_loadEventById($id);

				if (!is_object($event))
				{

					return NULL;

				}

				$submit = "Change trulala Event";
			
			}

			

			//create HTML

return <<<FORM_MARKUP

			<form action="assets/inc/process.inc.php" method="post">
				
				<fieldset>
					
					<legend>$submit</legend>

					<lable for="event_title">Name of Event Trulala</lable>

					<input type="text" name="event_title" id="event_title"
						value="$event->title"/>

					<lable for="event_start">Time to Start Trulala</lable>

					<input type="text" name="event_start" id="event_start"
						value="$event->start"/>

					<lable for="event_end">Time to End Trulala</lable>

					<input type="text" name="event_end" id="event_end"
						value="$event->end"/>

					<lable for="event_discription">Discription Trulala</lable>

					<textarea name="event_discription" 
					id="event_discription">$event->description</textarea>

					<input type="hidden" name="event_id" value="$event->id"/>

					<input type="hidden" name="token" value="$_SESSION[token]"/>

					<input type="hidden" name="action" value="event_edit"/>

					<input type="submit" name="event_submit" value="$submit"/>

					or <a href="./">Cancel</a>
					
				</fieldset>


			</form>

FORM_MARKUP;

		}


		//check , edit and save events in form

		public function processForm()
		{

			//exit if action is wrong

			if ($_POST['action']!='event_edit')
			{
				return "Incorrect try to call ProcessForm()";
			}

			//took data from form

			$title = htmlentities($_POST['event_title'], ENT_QUOTES);

			$desc = htmlentities($_POST['event_description'], ENT_QUOTES);

			$start = htmlentities($_POST['event_start'], ENT_QUOTES);

			$end = htmlentities($_POST['event_end'], ENT_QUOTES);

			//if id not taken, create new event 

			if (empty($_POST['event_id']))
			{
				
				$sql ="INSERT INTO `events`( `event_title`, `event_desc`, `event_start`, `event_end`)
					 VALUES (:title, :description, :start, :end)";

			}

			//update event if it change

			else
			{

				$id = (int) $_POST['event_id'];

				$sql = "UPDATE `events`  SET 
							
						`event_title`=:title, 
						
						`event_desc`=:description, 

						`event_start`=:start, 

						`event_end`=:end
						WHERE `event_id`=$id";	

			}

			try
			{

				$stmt = $this->db->prepare($sql);
// print_r($stmt);exit;
				$stmt->bindParam(":title", $title, PDO::PARAM_STR);

				$stmt->bindParam(":description", $desc, PDO::PARAM_STR);

				$stmt->bindParam(":start", $start, PDO::PARAM_STR);

				$stmt->bindParam(":end", $end, PDO::PARAM_STR);

				$success = $stmt->execute();
				//print_r($stmt->errorInfo());exit;
				$stmt->closeCursor();

				return TRUE;

			}

			catch (Exception $e)
			{

				return $e->getMessage();

			}


		}

		//delete event function

		public function confirmDelete($id)
		{

			// check that ID was send

			if (empty($id)) { return NULL; }


			// check that ID is integer

			$id = preg_replace('/[^0-9]/', '', $id);

			//Check data from form if confrm form where with mark

			if (isset($_POST['comfirm_delete']) && $_POST['token']==$_SESSION['token'])
			{
				
				if ($_POST['confirm_delete']=="Yes I agree!")
				{
						
					$sql = "DELETE FROM `events` WHERE `event_id`=':id LIMIT 1'";
					

					try
					{

						$stmt = $this->db->prepare($sql);

						$stmt->bindParam(":id", $id, PDO::PARAM_INT);

						$stmt->execute();

						$stmt->closeCursor();

						header("Location: ./");

						return;
						
						}

						catch (Exception $e)
						{

							return $e->getMessage();

						}

				}
			else
				{

				header("Location: ./");

				return;

				}
			}

			$event = $this->_loadEventById($id);

				if ( !is_object($event)) { header("Location: ./"); }

return<<<CONFIRM_DELETE

		<form action="confirmdelete.php" method="post">
			<h2> You realy want delete "$event->title"? </h2>

			<p><strong>Deleted event<strong> Will not restore</strong></p>

			<p>
				<input type="submit" name="confirm_delete"
					value="Yes I agree!"/>

				<input type="submit" name="confirm_delete"
					value="NO,It was JOKE"/>

				<input type="hidden" name="event_id"
					value="$event->id"/>

				<input type="hidden" name="token"
					value="$_SESSION[token]"/>
			</p>
		</form>

CONFIRM_DELETE;

		}
		
		// download all events treat month into array  

		private function _createEventObj()
		{

			// download event array

			$arr = $this -> _loadEventData();

			$events = array();

			foreach ($arr as $event)
			{
				
				$day = date('j', strtotime($event['event_start']));

				try
				{

					$events[$day][] = new Event($event);

				}

				catch (Exception $e)
				{
					die($e -> getMessage());
				}
			}

			return $events;

		}

		

		// return obj from solo massive

		private function _loadEventById($id)
		
		{

			// if ID doesn't catch we'll have NULL

			if (empty($id))
			
			 {
					return NULL;
			 } 

			 // download massive event

			 $event = $this -> _loadEventData($id);

			 //get event object

			 if (isset($event[0]))

			 {
			 	return new Event($event[0]);
			 }

			 else

			 {
			 	return NULL;
			 }

		}

		private function _adminGeneralOptions()
		{

return <<<ADMIN_OPTIONS

			<a href ="admin.php" class ="admin">+Add bew message !!!!!!!</a>

ADMIN_OPTIONS;

		}

		//edit and delete options by ID

		private function _adminEntryOptions($id)
		{

return <<<ADMIN_OPTIONS
		
	<div class="admin_options">
		<form action="admin.php" method="post">
			<p>
				<input type="submit" name="edit_event"
					value="Edit this PAGE!"/>

				<input type="hidden" name="event_id"
					value="$id"/>
			</p>
		</form>

		<form action="confirmdelete.php" method="post">
			<p>
				<input type="submit" name="delete_event"
					value="Delete EveNT"/>

				<input type="hidden" name="event_id"
					value="$id"/>
			</p>
		</form>
	</div> <!--end.admin-options-->

ADMIN_OPTIONS;

		}


	}


  ?>