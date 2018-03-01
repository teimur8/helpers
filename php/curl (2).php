<?php

public static function curl($url, array $params = [], $method = 'get')
{
    try {
        $ch = curl_init();

        if (FALSE === $ch)
            throw new \Exception('failed to initialize');

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if($method == 'post'){
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        $content = curl_exec($ch);

        if (FALSE === $content)
            throw new \Exception(curl_error($ch), curl_errno($ch));

        return $content;

    } catch(\Exception $e) {

        trigger_error(sprintf(
            'Curl failed with error #%d: %s',
            $e->getCode(), $e->getMessage()),
            E_USER_ERROR);

    }

}