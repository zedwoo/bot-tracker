<?php

namespace Zedwoo\BotTracker;


class BotTracker
{
    private $requestUserAgent;
    private $requestIp;
    private $requestTimestamp;
    private $requestStatusCode = 200;
    private $logMessage;


    private $splunkProjectId;
    private $splunkAccessToken;
    private $splunkHostName;
    private $splunkSourceType = 'access_combined';
    private $botsPattern = '/googlebot/i';

    function __construct($splunkProjectId, $splunkAccessToken, $splunkApiHostname)
    {
        $this->setSplunkProjectId($splunkProjectId);
        $this->setSplunkAccessToken($splunkAccessToken);
        $this->setSplunkHostName($splunkApiHostname);
    }

    public function doLog()
    {
        if ($this->isBot() === true){
			$result = $this->sendRequest();
		} else {
			$result = false;
		}
		return $result;
    }

    private function sendRequest()
    {
		$result = false;
        if (function_exists('curl_init')) {
            $url = 'https://' . $this->getSplunkHostName()
                . '/1/inputs/http?index='
                . $this->getSplunkProjectId()
                . '&sourcetype='
                . $this->splunkSourceType
                . '&host="' . $this->getRequestHost() . '"';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 1);
            curl_setopt($ch, CURLOPT_USERPWD, "x:" . $this->getSplunkAccessToken());
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type' => 'text/plain'));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->getLogMessage());

            @curl_exec($ch);
            $info = curl_getinfo($ch);
			curl_close($ch);
			if(!empty($info['CURLINFO_HTTP_CODE']) AND $info['CURLINFO_HTTP_CODE'] == 200){
				$result = true;
			}
        }
		return $result;
    }

    /**
     * @return string
     */
    private function getLogMessage()
    {
        $this->logMessage = $this->getRequestIp() .
            ' - - [' . $this->getDate() . '] "'
            . $this->getRequestMethod() . ' ' . $this->getRequestUrl() . ' ' . $this->getRequestHttpProtocol() . '" '
            . $this->getRequestStatusCode()
            . ' - "" '
            . '"' . $this->getRequestUserAgent() . '"';
        return $this->logMessage;
    }

    /**
     * @return string
     */
    public function getRequestHttpProtocol()
    {
        if (!empty($_SERVER['SERVER_PROTOCOL'])) {
            $result = $_SERVER['SERVER_PROTOCOL'];
        } else {
            $result = '-';
        }
        return $result;
    }

    /**
     * @return string
     */
    public function getRequestMethod()
    {
        if (!empty($_SERVER['REQUEST_METHOD'])) {
            $result = $_SERVER['REQUEST_METHOD'];
        } else {
            $result = 'GET';
        }
        return $result;
    }

    /**
     * @param integer $requestStatusCode
     */
    public function setRequestStatusCode($requestStatusCode)
    {
        $this->requestStatusCode = $requestStatusCode;
    }

    /**
     * @return integer
     */
    public function getRequestStatusCode()
    {
        return $this->requestStatusCode;
    }

    /**
     * @return bool
     */

    public function isBot()
    {
        if (preg_match($this->getBotsPattern(), $this->getRequestUserAgent())) {
            //Todo Check for ReverseDNS
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $botsPattern
     */
    public function setBotsPattern($botsPattern)
    {
        $this->botsPattern = $botsPattern;
    }

    /**
     * @return string
     */
    private function getBotsPattern()
    {

        return $this->botsPattern;
    }

    /**
     * @param string $splunkAccessToken
     */
    public function setSplunkAccessToken($splunkAccessToken)
    {
        $this->splunkAccessToken = $splunkAccessToken;
    }

    /**
     * @return string
     */
    private function getSplunkAccessToken()
    {
        return $this->splunkAccessToken;
    }

    /**
     * @param string $splunkHostName
     */
    private function setSplunkHostName($splunkHostName)
    {
        $this->splunkHostName = $splunkHostName;
    }

    /**
     * @return string
     */
    private function getSplunkHostName()
    {
        return $this->splunkHostName;
    }

    /**
     * @param string $splunkProjectId
     */
    private function setSplunkProjectId($splunkProjectId)
    {
        $this->splunkProjectId = $splunkProjectId;
    }

    /**
     * @return string
     */
    private function getSplunkProjectId()
    {
        return $this->splunkProjectId;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        if ($this->getRequestTimestamp()) {
            $time = $this->getRequestTimestamp();
        } else {
            $time = time();
        }
        return date('d/M/Y:h:i:s O', $time);
    }

    /**
     * @return string
     */
    private function getRequestIp()
    {
        if (!$this->requestIp AND !empty($_SERVER['REMOTE_ADDR'])) {
            $this->requestIp = $_SERVER['REMOTE_ADDR'];
        }
        return $this->requestIp;
    }

    /**
     * @return string
     */

    private function getRequestScheme()
    {
        if (isset($_SERVER['HTTPS']) AND ($_SERVER['HTTPS'] == 'on' OR $_SERVER['HTTPS'] === true)
        ) {
            return 'https';
        }
        return 'http';
    }

    /**
     * @return string
     */

    private function getRequestHost()
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            return $_SERVER['HTTP_HOST'];
        }
        return '';
    }

    /**
     * @return string
     */

    private function getRequestScriptName()
    {
        $url = '';
        if (!empty($_SERVER['PATH_INFO'])) {
            $url = $_SERVER['PATH_INFO'];
        } else if (!empty($_SERVER['REQUEST_URI'])) {
            if (($pos = strpos($_SERVER['REQUEST_URI'], '?')) !== false) {
                $url = substr($_SERVER['REQUEST_URI'], 0, $pos);
            } else {
                $url = $_SERVER['REQUEST_URI'];
            }
        }
        if (empty($url)) {
            $url = $_SERVER['SCRIPT_NAME'];
        }

        if ($url[0] !== '/') {
            $url = '/' . $url;
        }
        return $url;

    }

    /**
     * @return string
     */

    private function getRequestQueryString()
    {
        $result = '';
        if (isset($_SERVER['QUERY_STRING'])
            && !empty($_SERVER['QUERY_STRING'])
        ) {
            $result .= '?' . $_SERVER['QUERY_STRING'];
        }
        return $result;
    }

    /**
     * @return string
     */

    private function getRequestUrl()
    {
        $result = $this->getRequestScheme() . '://'
            . $this->getRequestHost()
            . $this->getRequestScriptName()
            . $this->getRequestQueryString();

        return $result;

    }

    /**
     * @return string
     */
    public function getRequestUserAgent()
    {
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            $this->requestUserAgent = $_SERVER['HTTP_USER_AGENT'];
        }
        return $this->requestUserAgent;
    }

    /**
     * @param string $requestTimestamp
     */
    public function setRequestTimestamp($requestTimestamp)
    {
        $this->requestTimestamp = $requestTimestamp;
    }

    /**
     * @return string
     */
    private function getRequestTimestamp()
    {
        return $this->requestTimestamp;
    }
}
