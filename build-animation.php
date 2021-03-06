<?php

$apiKey = (string)$argv[1];
$weatherArg = (string)$argv[2];


$fileBaseScss = __DIR__ . '/src/Resources/app/storefront/src/scss/base.scss';
$sunImport = "@import 'animations/sun.scss'";
$rainImport = "@import 'animations/rain.scss'";


$weather = getWeather('Holzkirchen,Bavaria', $apiKey);
$data = json_decode($weather, true);
$condition = (string)$data['weather'][0]['main'];

if (empty($condition)) {
    return;
}

$isRainy = (strtolower($condition) === 'rain');
$isSunny = !$isRainy;

if ($weatherArg === 'rain') {
    $isRainy = true;
    $isSunny = false;
} else if ($weatherArg === 'sun') {
    $isSunny = true;
    $isRainy = false;
}

$baseScssContent = file_get_contents($fileBaseScss);

# remove all comments for the animation to have a clean start
$baseScssContent = str_replace('//', '', $baseScssContent);

if (!$isRainy) {
    $baseScssContent = str_replace($rainImport, '//' . $rainImport, $baseScssContent);
}

if (!$isSunny) {
    $baseScssContent = str_replace($sunImport, '//' . $sunImport, $baseScssContent);
}

file_put_contents($fileBaseScss, $baseScssContent);


/**
 * @param $city
 * @param $apiKey
 * @return bool|string
 */
function getWeather($city, $apiKey)
{
    $jsonurl = "https://api.openweathermap.org/data/2.5/weather?q=" . $city . "&appid=" . $apiKey;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $jsonurl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);

    curl_close($ch);

    return $output;
}