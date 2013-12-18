#bot-tracker


PHP-Library for logging Google-Bot-Visits to splunkstorm ( it's free: https://www.splunkstorm.com/ )

##Installation
Make sure PHP >= 5.3.4 is available on your server.

###With Composer
If you are using composer ( http://getcomposer.org/ ), you will get the freshest version, adding this to the require-section in your composer.json:

    "zedwoo/bot-tracker": "0.0.*"

###File-include
If you don't like Composer, you can include a single file in your project. You will find it under "src/Zedwoo/BotTracker/BotTracker.php".

###Git clone
If you want to use the dev-version with "git clone", i think, you know how to do it ;)

##How to use
You need a free account from splunkstorm ( https://www.splunkstorm.com/ ‎):

Easy Logging:

    $botLog = new \Zedwoo\BotTracker\BotTracker($splunkProjectId, $splunkAccessToken, $splunkApiHostname);
    $botLog->doLog();

And Logging with 404-Pages

    $botLog = new \Zedwoo\BotTracker\BotTracker($splunkProjectId, $splunkAccessToken, $splunkApiHostname);
    if(is_404er){
    $botLog->setRequestStatusCode(404);
    }
    $botLog->doLog();

You have to implement the "is_404er" by your own ;-)

##Contributing
You want to help? It's so easy:

1. Fork it
2. Create your feature branch (git checkout -b my-new-feature)
3. Commit your changes (git commit -am 'Add some feature')
4. Push to the branch (git push origin my-new-feature)
5. Create new Pull Request


