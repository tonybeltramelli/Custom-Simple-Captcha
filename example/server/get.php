<?php

include_once '../../php/CustomSimpleCaptcha.php';

$type = $_GET["type"];

$QUESTION_PATH = "config/challenge.".$type;

//get captcha challenge

$captcha = new CustomSimpleCaptcha($QUESTION_PATH);
echo $captcha->getChallenge();

?>