<?php

class DomainService
{
    public function validateDomain($domain)
    {
        if(isset($domain)) {
            return htmlspecialchars($domain);
        } else {
            return false;
        }
    }

    public function addHttpOrHttpsToGivenDomain($domain, $type = 1)
    {
        if($type == 1) {
            return "http://" . $domain;
        } elseif ($type == 2) {
            return "https://" . $domain;
        }

        return false;
    }

    public function changeHttpsToHttpInGivenDomain($domain)
    {
        return str_replace('https://', 'http://', $domain);
    }

    public function findOnlyDomainInGivenArr($array)
    {
        $container = [];

        if(isset($array)) {
            $container =  parse_url($array);
        }

        if(!empty($container)) {
            return $container['host'];
        }

        return false;
    }
}