<?php 
/**
 * 
 */
class TestClass
{
	public $prop1 ="Something obout class";

	public function __construct()
	{
		echo 'Created Class"', _CLASS_ ,'"!<br/>';
	}

	public function __destruct()
	{
		echo 'Destroyed Class"', _CLASS_ ,'"!<br/>';
	}

	public function __toString()
	{
		echo "Use method _toString: ";
		return $this->getProperty();
	}

	public function setProperty($newval)
	{
		$this->prop1 = $newval;
	}

	public function getProperty()
	{
		return $this->prop1 . "<br/>";
	}	
	


}
/**
 * example extend in OOP
 */
class Test2Class extends TestClass
{
	
	public function newMethod()
	{
		echo 'Created Class for example 123 hahaha "', _CLASS_ ,'"!<br/>';
	}
}

$newobj= new Test2Class;
echo $newobj->newMethod();
echo $newobj->getProperty();
//unset($obj);
echo "The End . <br/>";
?>