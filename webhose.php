<?php

class Webhose
{
    private static $API_URL = "http://webhose.io";
    private static $API_URL_PARAMS = "/%s?format=json&token=%s";
    private static $API_KEY = null;
    private static $ECHO_REQUEST_URL = false;

    private static $NEXT = null;

    private static $CURLOPTS = array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_RETURNTRANSFER => true
    );

    /**
     * @param string $api_key
     */
    public static function config($api_key)
    {
        self::$API_KEY = $api_key;
    }

    /**
     * @param string $query_url
     * @return mixed
     */
    private static function fetch_request($query_url)
    {
        if(self::$ECHO_REQUEST_URL)
            echo "<p>" . $query_url . "</p>";

        $curl = curl_init($query_url);
        curl_setopt_array($curl, self::$CURLOPTS);
        $json = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($json);


        self::$NEXT = isset($result->next) ? self::$API_URL . $result->next : null;

        return $result;
    }

    /**
     * @param bool $enable_debug
     */
    public static function enable_debug($enable_debug)
    {
        self::$ECHO_REQUEST_URL = $enable_debug;
    }

    /**
     * @param string $type
     * @param ArrayObject $param_dict
     * @return mixed|null
     */
    public static function query($type, $param_dict)
    {
        if(self::$API_KEY == null) return null;
        $queryURL = self::$API_URL . sprintf(self::$API_URL_PARAMS, $type, self::$API_KEY);

        foreach($param_dict as $key=> $value)
            $queryURL .= sprintf("&%s=%s", $key, urlencode($value));

        return self::fetch_request($queryURL);
    }

    /**
     * @return mixed|null
     */
    public static function get_next()
    {
        if(self::$API_KEY == null) return null;
        if(self::$NEXT == null) return null;
        return self::fetch_request(self::$NEXT);
    }
}

?>