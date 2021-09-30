<?php

namespace GoogleRecaptcha\Services\HttpClient;

class HttpClient
{


    /**
     * @param string $url
     * @param array $headers
     * @param string $content
     * @return bool|string
     */
    public function post(string $url, array $headers, string $content): HttpResponse
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POST, 1);

        $this->client->setOption(CURLOPT_HTTPHEADER, $headers);

        $this->client->setOption(CURLOPT_CUSTOMREQUEST, 'POST');
        $this->client->setOption(CURLOPT_POSTFIELDS, $content);


        $response = curl_exec($ch);

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return new HttpResponse(
            $httpcode,
            $response
        );
    }

}