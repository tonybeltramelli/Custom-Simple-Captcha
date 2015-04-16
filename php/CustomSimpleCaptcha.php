<?php

/**
 * @author author Tony Beltramelli - http://www.tonybeltramelli.com
 */
 
class CustomSimpleCaptcha {
    
    private $_challengePath;
	private $_questions;
    
    public function __construct($challengePath) 
    {
    	$this->_challengePath = $challengePath;
    }
    
    public function getChallenge()
    {
       	$questions = $this->_getQuestions();
		
		$index = rand(0, count($questions) - 1);
		
		$challenge = $questions[$index];
		$challenge = substr($challenge, 0, strrpos($challenge, "="));
		
		$result = array("index" => $index, "challenge" => $challenge);
	
		return json_encode($result);
    }
	
	public function checkAnswer($index, $providedAnswer)
	{		
		$questions = $this->_getQuestions();
		
		if(!is_numeric($index) || $index >= count($questions)) return $this->_getStatusMessage(2);
		
		$expectedAnswer = $questions[$index];
		$expectedAnswer = substr($expectedAnswer, strrpos($expectedAnswer, "=") + 1, strlen($expectedAnswer));
		
		$isMatched = strcasecmp($providedAnswer, $expectedAnswer) == 0;
		
		return $this->_getStatusMessage($isMatched);
	}
	
	private function _getQuestions()
	{
		if(isset($this->_questions)) return $this->_questions;
		
		$questions = file_get_contents($this->_challengePath);
		$this->_questions = explode("\n", $questions);
		array_shift($this->_questions);
		
		return $this->_questions;
	}
	
	private function _getStatusMessage($type)
	{
		$status = "error";
		
		switch ($type)
		{
			case 0:
				$status = "fail";
				break;
			case 1:
				$status = "success";
				break;
			default:
				$status = "error";
		}
		
		$result = array("status" => $status);
		
		return json_encode($result);
	}
	
    public function __destruct() 
    {
    }
}

?>
