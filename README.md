bot-tracker
===========

Library for logging Google-Bot-Visits to splunkstorm ( https://www.splunkstorm.com/ )

For Composer Users:
<code>
$botLog = new \Zedwoo\BotTracker\BotTracker($splunkProjectId, $splunkAccessToken, $splunkApiHostname);
$botLog->doLog();
</code>

And for Error-Pages
<code>
$botLog = new \Zedwoo\BotTracker\BotTracker($splunkProjectId, $splunkAccessToken, $splunkApiHostname);
$botLog->setRequestStatusCode(404);
$botLog->doLog();
</code>

