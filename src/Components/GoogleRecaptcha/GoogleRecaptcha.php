<?php

namespace GoogleRecaptcha\Components\GoogleRecaptcha;

use GoogleRecaptcha\Services\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;

class GoogleRecaptcha
{

    /**
     * @var string
     */
    private $siteKey;

    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var float
     */
    private $minScore;


    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var string
     */
    private $verificationURL = "https://www.google.com/recaptcha/api/siteverify";


    /**
     * @param string $siteKey
     * @param string $secret
     * @param float $minScore
     * @param HttpClient $client
     */
    public function __construct(string $siteKey, string $secret, float $minScore, HttpClient $client)
    {
        $this->siteKey = $siteKey;
        $this->secretKey = $secret;
        $this->minScore = $minScore;
        $this->client = $client;
    }

    /**
     * Gets the Recaptcha Site Key for the html frontend control.
     *
     * @return string
     */
    public function getSiteKey(): string
    {
        return $this->siteKey;
    }

    /**
     * @param string $action
     * @param string $token
     * @return bool
     */
    public function verifyToken(string $action, string $token): bool
    {
        if (empty($action) || empty($token)) {
            return false;
        }

        $data = [
            'secret' => $this->secretKey,
            'response' => $token
        ];

        $headers = [
            "Content-type" => "application/x-www-form-urlencoded",
        ];


        $response = $this->client->post($this->verificationURL, $headers, http_build_query($data));

        $responseStatus = $response->getStatusCode();

        if ($responseStatus < 200 || $responseStatus >= 300) {
            return false;
        }

        /** @var array $responseKeys */
        $responseKeys = json_decode($response->getBody(), true);

        if (!$this->isResponseDataExisting($responseKeys)) {
            return false;
        }

        # verify if we have the same action.
        # if not then there is something weird (injections?!)
        if ($action !== $responseKeys['action']) {
            return false;
        }

        /** @var float $score */
        $score = $responseKeys['score'];

        # check if we have success along
        # with the correct minimum score
        if ($responseKeys['success'] && $score >= $this->minScore) {
            return true;
        }

        return false;
    }

    /**
     * @param array $responseKeys
     * @return bool
     */
    private function isResponseDataExisting(array $responseKeys): bool
    {
        if (!array_key_exists('success', $responseKeys)) {
            return false;
        }

        if (!array_key_exists('action', $responseKeys)) {
            return false;
        }

        if (!array_key_exists('score', $responseKeys)) {
            return false;
        }

        return true;
    }

}
