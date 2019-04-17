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
			$sql = "SELECT 'event_id', 'event_title', 'event_desc', 
			'event_start', 'event_end' FROM 'events'";
			if (!empty($id)) 
			{
				
				$sql .= "WHERE 'event_id' =: id LIMIT 1";

			} 

			else
			
				// in another case download all events in this month
			
			{

				//find first and last days in month

				$start_ts = mktime(0, 0, 0, $this -> _m, 1,$this -> _y);
				$end_ts = mktime(23, 59, 59, $this -> _m + 1, 0, $this -> _y);
				$start_date = date('Y-m-d H:i:s', $start_ts);
				$end_date = date('Y-m-d H:i:s', $end_ts);

				//filter needfull events for this month

				$sql .= "WHERE 'event_start' BETWEN '$start_date'
				AND '$end_date' ORDER BY 'event_start'";

			}

			try

			{
				$stmt = $this -> db ->prepare($sql);

				// connect parameter if id where transferred 

				if (!empty($id)) 
				{
					$stmt -> bindParam(" :id", $id, PDO::PARAM_INT);

				}

				$stmt -> execute();
				$results = $stmt -> fetchAll(PDO::FETCH_ASSOC);
				$stmt -> closeCursor();

				return $results;
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

			return $html;



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

			$end = date('g:ia', strtotime($event ->end));

			// generate and return markup

			return "<h2>$event->title</h2>"
				. "\n\t<p class=\"dates\">$date, $start&mdash;#end</p>"
				. "\n\t<p>$event->description</p>";

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


	}


  ?>