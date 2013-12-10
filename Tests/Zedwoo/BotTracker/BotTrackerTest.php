<?php

namespace Zedwoo\BotTracker\Tests;

use Zedwoo\BotTracker\BotTracker;

class BotTrackerTest extends \PHPUnit_Framework_TestCase {

    public function testIsBot(){
        $tracker = new BotTracker($_SERVER['SPLUNK_PROJECT_ID'],$_SERVER['SPLUNK_ACCESS_TOKEN'],$_SERVER['SPLUNK_API_HOSTNAME']);
        /*
         * Check for Googlebots
         */
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)';
        $this->assertTrue($tracker->isBot());

        $_SERVER['HTTP_USER_AGENT'] = 'Googlebot-News';
        $this->assertTrue($tracker->isBot());

        $_SERVER['HTTP_USER_AGENT'] = 'Googlebot-Image/1.0';
        $this->assertTrue($tracker->isBot());

        $_SERVER['HTTP_USER_AGENT'] = 'Googlebot-Video/1.0)';
        $this->assertTrue($tracker->isBot());

        $_SERVER['HTTP_USER_AGENT'] = '(compatible; Googlebot-Mobile/2.1; +http://www.google.com/bot.html)';
        $this->assertTrue($tracker->isBot());

        /*
         * Check Browser Useragents
         */
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:25.0) Gecko/20100101 Firefox/25.0';
        $this->assertFalse($tracker->isBot());
    }

    public function testdoLog(){

        $_SERVER['REMOTE_ADDR'] = '333.333.333.333';
        $_SERVER['HTTP_HOST'] = 'www.zedwoo.de';
        $_SERVER['REQUEST_URI'] = 'index.php?id=7&sid=989';
        $_SERVER['QUERY_STRING'] = 'id=7&sid=989';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.0';
        $_SERVER['HTTP_USER_AGENT'] = 'googlebot';

        $tracker = new BotTracker($_SERVER['SPLUNK_PROJECT_ID'],$_SERVER['SPLUNK_ACCESS_TOKEN'],$_SERVER['SPLUNK_API_HOSTNAME']);
        $tracker->doLog();
        //Todo make Test for doLog
    }
}
