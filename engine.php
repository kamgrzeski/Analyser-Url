<?php 

ini_set('max_execution_time', 0); //max script run 

if(isset($_POST['submit']))
{
    if (is_uploaded_file($_FILES['linkstxt']['tmp_name']))
    {
        /* important variables */
        $file = $_FILES['linkstxt']['tmp_name'];   // get file form post
        $filecontent = file_get_contents($file);   // red uploaded file
        $fileopen = array_filter(array_map('trim', explode(PHP_EOL, $filecontent))); // export data from file to array
        $fileopen = array_values($fileopen); // reset index array
        $domain = htmlspecialchars($_POST['domain']); // secure domain 
        $counter = count($fileopen);               // array count
        $regex = "((https?|ftp)\:\/\/)?";
        $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?";
        $regex .= "([a-z0-9-.]*)\.([a-z]{2,3})";
        $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?";
        $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?";
        $regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?";
        $temper = '';
        $http_status_codes = array( // array of response code's
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
                599 => 'Server Error: Network connect timeout error',
            );

        /* try to find invalid domain adress */
        if(!preg_match('/http:\/\//',$domain))
        {
            if (!preg_match("@^[hf]tt?ps?://@", $domain))
            {
                $domain = "http://" . $domain;
            }
        }
        if(!preg_match("/^$regex$/i", $domain))
        {
            echo '<div class="alert alert-danger">Plase write valid domain adress.</div>';
            die();
        }

        /* if domain haven't 'http://' */
        foreach ($fileopen as &$value)
        {
            if (!preg_match("~^(?:f|ht)tps?://~i", $value))
            {
                $value = "https://" . $value;
            }
                $value = str_replace('https://', 'http://', $value); //change https:// to http://
        }

        $parse = parse_url($domain); // find only domain in file
        $new_domain = $parse['host'];

        /* download all links from source code */
        foreach($fileopen as $key => $filelinks)
        {
            /* implement curl */
            $ch = curl_init($filelinks);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 1);
            curl_exec($ch);
            $info = curl_getinfo($ch);
            $code_nr = $info['http_code'];
            $code_desc = $http_status_codes[$code_nr];
            curl_close($ch);

            $links = []; 
            $found = false; 

            echo '<hr>';
            if(!preg_match("/^$regex$/i", $filelinks))
            {
                $wrong_url = $filelinks;
                echo '<div class="alert alert-danger">System find invalid domain adress in file: <b>'.str_replace('http://', '', $wrong_url).'</b></div>';
                continue;
            }
            /* implement DOMDocument */
            $dom = new DOMDocument();
            @$dom->loadHTMLFile($filelinks);

            $arr = $dom->getElementsByTagName('a');
            $arr2 = $dom->getElementsByTagName('img');

            foreach($arr as $item) { // find all links
                    $anchor = $item->nodeValue;
                    $alt = $item->getAttribute('alt');
                    $rel = $item->getAttribute('rel');
                    $href = $item->getAttribute('href');
                    $src = $item->getAttribute('src');
                    $links[] = [
                        'anchor' => $anchor,
                        'href' => $href,
                        'alt' => $alt,
                        'rel' => $rel,
                        'src' => $src
                    ];
                }
            foreach($arr2 as $item) { // find all img
                    $anchor = $item->nodeValue;
                    $alt = $item->getAttribute('alt');
                    $rel = $item->getAttribute('rel');
                    $href = $item->getAttribute('href');
                    $src = $item->getAttribute('src');
                    $links[] = [
                        'anchor' => $anchor,
                        'href' => $href,
                        'alt' => $alt,
                        'rel' => $rel,
                        'src' => $src
                    ];
                }

            echo '<b>Address URL: </b>' . $filelinks . ' | ';
            echo '<b>Server response: </b> '.$code_desc.'  (Code: '.$code_nr.') | ';

            if(!empty($info['redirect_url']))
            {
                echo ' - <font color="blue"><b>'.$info['redirect_url'].' | </font></b>';
            }
                echo '<b>Search link: </b>' . $domain . ' <br><b>Status detection: </b>';

            /* przeszukanie tablicy pod względem linku/img */
            foreach ($links as $link)
            {
                /* simple counter with js */
                $jscounter = $key+1;
                $percent = intval($key/$counter * 100);
                $percent_decimal = $percent / 100;
                echo '<script language="javascript">
                document.getElementById("information").innerHTML=" Status: '.$jscounter.' url(i) processed.";
                </script>';

                /* usage NProgress.js */
                echo '<script language="javascript">
                NProgress.set('.$percent_decimal.');
                NProgress.inc();
                NProgress.configure({ ease: \'ease\', speed: 1500 });
                </script>';

                $find_href = preg_match("@$new_domain@i", $link['href']); 
                $find_img = preg_match("@$new_domain@i", $link['src']); 

                if($find_href)
                {
                    $save_txt = file_put_contents('detected_url.txt', $link['href']); // save all links to *.txt

                    $found = true;
                    if($link['href'] != '')
                    {
                        echo '<br><font color="green"><b>Detected - href : </b></font>'.$link['href'].'';
                        echo '<b> | Anchor: </b>';
                        $found = true;
                    }
                    if ($link['anchor'] != "")
                    {
                        echo $link['anchor'];
                    }
                    else
                    {
                        echo 'NO PARAMETER';
                    }
                    echo '<b> | Rel: </b>';

                    if ($link['rel'] != "")
                    {
                        echo $link['rel'];
                    }
                    else
                    {
                        echo 'NO PARAMETER';
                    }
                }

                if($find_img)
                {
                    $found = true;
                    if($link['src'] != '')
                    {
                        echo '<br><font color="green"><b>Detected - graphics : </b></font>'.$link['src'].'';
                        echo '<b> | Alt: </b>';
                        $found = true;
                    }
                    if ($link['alt'] != "")
                    {
                        echo $link['alt'];
                    }
                    else
                    {
                        echo 'NO PARAMETER';
                    }
                    echo '<b> | Src: </b>';

                    if ($link['src'] != "")
                    {
                        echo $link['src'];
                        echo '<br>';
                    }
                    else
                    {
                        echo 'NO PARAMETER';
                    }
                }
            }
            if (!$found)
            {
                echo '<font color="red"><b>Undetected</b></font>';
            }
        }
        
        echo '<script>NProgress.done();</script>';
    }
    else
    {
        echo '<div class="alert alert-danger">choose a file!</div>';
    }
}

?>