<?php

require_once('../src/Zedwoo/BotTracker/BotTracker.php');

//$botLog = new \Zedwoo\BotTracker\BotTracker::Log('1','2','3');
$botLog = new \Zedwoo\BotTracker\BotTracker($splunkProjectId, $splunkAccessToken, $splunkApiHostname);
$botLog->setRequestStatusCode(404);