<?php// représente une valeur datée
class DatedValue{
	public $Value;
	
	// DateTime
	public $Time;
	
	public function __construct($Value, $Timestamp){
		$this->Value = $Value;
		$this->Time = new DateTime();
		$this->Time->setTimestamp($Timestamp);
	}
}
?>