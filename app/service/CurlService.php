<?php

class CurlService
{
    //CURL settings
    private $CURLOPT_RETURNTRANSFER = true;

    private $CURLOPT_FOLLOWLOCATION = false;

    private $CURLOPT_CONNECTTIMEOUT = 0;

    private $CURLOPT_TIMEOUT = 1;

    private $allStatusCodes = [
        0 => 'Curl error: Could not resolve host',
        100 => 'Informational: Continue',
        101 => 'Informational: Switching Protocols',
        102 => 'Informational: Processing',
        200 => 'Successful: OK',
        201 => 'Successful: Created',
        202 => 'Successful: Accepted',
        203 => 'Successful: Non-Authoritative Information',
        204 => 'Successful: No Content',
        205 => 'Successful: Reset Content',
        206 => 'Successful: Partial Content',
        207 => 'Successful: Multi-Status',
        208 => 'Successful: Already Reported',
        226 => 'Successful: IM Used',
        300 => 'Redirection: Multiple Choices',
        301 => 'Redirection: Moved Permanently',
        302 => 'Redirection: Found',
        303 => 'Redirection: See Other',
        304 => 'Redirection: Not Modified',
        305 => 'Redirection: Use Proxy',
        306 => 'Redirection: Switch Proxy',
        307 => 'Redirection: Temporary Redirect',
        308 => 'Redirection: Permanent Redirect',
        400 => 'Client Error: Bad Request',
        401 => 'Client Error: Unauthorized',
        402 => 'Client Error: Payment Required',
        403 => 'Client Error: Forbidden',
        404 => 'Client Error: Not Found',
        405 => 'Client Error: Method Not Allowed',
        406 => 'Client Error: Not Acceptable',
        407 => 'Client Error: Proxy Authentication Required',
        408 => 'Client Error: Request Timeout',
        409 => 'Client Error: Conflict',
        410 => 'Client Error: Gone',
        411 => 'Client Error: Length Required',
        412 => 'Client Error: Precondition Failed',
        413 => 'Client Error: Request Entity Too Large',
        414 => 'Client Error: Request-URI Too Long',
        415 => 'Client Error: Unsupported Media Type',
        416 => 'Client Error: Requested Range Not Satisfiable',
        417 => 'Client Error: Expectation Failed',
        418 => 'Client Error: I\'m a teapot',
        419 => 'Client Error: Authentication Timeout',
        420 => 'Client Error: Method Failure',
        422 => 'Client Error: Unprocessable Entity',
        423 => 'Client Error: Locked',
        424 => 'Client Error: Failed Dependency',
        425 => 'Client Error: Unordered Collection',
        426 => 'Client Error: Upgrade Required',
        428 => 'Client Error: Precondition Required',
        429 => 'Client Error: Too Many Requests',
        431 => 'Client Error: Request Header Fields Too Large',
        444 => 'Client Error: No Response',
        449 => 'Client Error: Retry With',
        450 => 'Client Error: Blocked by Windows Parental Controls',
        451 => 'Client Error: Redirect',
        494 => 'Client Error: Request Header Too Large',
        495 => 'Client Error: Cert Error',
        496 => 'Client Error: No Cert',
        497 => 'Client Error: HTTP to HTTPS',
        499 => 'Client Error: Client Closed Request',
        500 => 'Server Error: Internal Server Error',
        501 => 'Server Error: Not Implemented',
        502 => 'Server Error: Bad Gateway',
        503 => 'Server Error: Service Unavailable',
        504 => 'Server Error: Gateway Timeout',
        505 => 'Server Error: HTTP Version Not Supported',
        506 => 'Server Error: Variant Also Negotiates',
        507 => 'Server Error: Insufficient Storage',
        508 => 'Server Error: Loop Detected',
        509 => 'Server Error: Bandwidth Limit Exceeded',
        510 => 'Server Error: Not Extended',
        511 => 'Server Error: Network Authentication Required',
        598 => 'Server Error: Network read timeout error',
        599 => 'Server Error: Network connect timeout error'
    ];

    private $code = 0;

    public function getStatusCodes()
    {
        return $this->allStatusCodes;
    }

    public function initCurl($content)
    {
        $ch = curl_init($content);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $this->CURLOPT_RETURNTRANSFER);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $this->CURLOPT_FOLLOWLOCATION);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->CURLOPT_CONNECTTIMEOUT);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->CURLOPT_TIMEOUT);
        curl_exec($ch);
        $info = curl_getinfo($ch);
        $code_nr = $this->getStatusCodeFromConnection($info);
        $httpStatusCode = $this->getStatusCodes();
        $code_desc = $httpStatusCode[$code_nr];
        $this->code = $code_nr;
        curl_close($ch);

        return $code_desc;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getStatusCodeFromConnection($conent)
    {
        return isset($conent['http_code']) ? $conent['http_code'] : null;
    }

    public function findHref($domain, $link)
    {
        return preg_match("@$domain@i", $link);
    }

    public function findImg($domain, $link)
    {
        return preg_match("@$domain@i", $link);
    }
}

