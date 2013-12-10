bot-tracker
===========

Library for logging Google-Bot-Visits to splunkstorm ( https://www.splunkstorm.com/ )

For Composer Users:

```
$botLog = new \Zedwoo\BotTracker\BotTracker($splunkProjectId, $splunkAccessToken, $splunkApiHostname);
$botLog->doLog();
```

And for Error-Pages

```
$botLog = new \Zedwoo\BotTracker\BotTracker($splunkProjectId, $splunkAccessToken, $splunkApiHostname);
$botLog->setRequestStatusCode(404);
$botLog->doLog();
```

