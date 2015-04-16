<?php

include_once '../../php/CustomSimpleCaptcha.php';

$type = $_POST["type"];

$QUESTION_PATH = "config/challenge.".$type;

//check captcha challenge response

$answer = $_POST["answer"];
$index = $_POST["index"];

$captcha = new CustomSimpleCaptcha($QUESTION_PATH);
echo $captcha->checkAnswer($index, $answer);

?>