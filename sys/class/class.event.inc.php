<?php
//save information about events
	class Event
	{

	// @var int - identify events

	public $id;
	
	//@var string - event name

	public $title;

	//event description

	public $description;

	// event start time 

	public $start;

	// event end time 

	public $end;

	// data and inf masive about event

	public function __construct($event)
	{

		if (is_array($event)) 
		{
			$this -> id = $event['event_id'];
			$this -> title = $event['event_title'];
			$this -> description = $event['event_desc'];
			$this -> start = $event['event_start'];
			$this -> end = $event['event_end'];

		}

		else
		{
			throw new Exception("NO DATA ABOUT EVENT , Wery sory about that!");
		}

	}


}

?>