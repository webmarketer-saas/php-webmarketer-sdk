<?php

namespace Webmarketer\Utils;

class Url
{
    /**
     * @param string $str
     * @return string
     */
    public static function base64Encode($str)
    {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }
}