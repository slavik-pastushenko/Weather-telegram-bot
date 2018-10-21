<?php

include('vendor/autoload.php');

include('TelegramBot.php');

include('Weather.php');

$telegram_api = new TelegramBot();

$weather_api = new Weather();

while (true) {

    sleep(2);

    $updates = $telegram_api->getUpdates();

    foreach ($updates as $update) {

        if (isset($update->message->location)) {

            $result = $weather_api->getWeather($update->message->location->latitude, $update->message->location->longitude);

            switch ($result->weather[0]->main) {
                case 'Clear':
                    $response = 'Wonderful weather!';
                    break;
                case 'Clouds':
                    $response = 'The weather is cloudy. Rain should not go, check the weather again a little later';
                    break;
                case 'Rain':
                    $response = 'Unfortunately, it will rain today. Take an umbrella or stay home';
                    break;
                default:
                    $response = 'I can not determine your location. Can you send the location again?';
            }
            $telegram_api->sendMessage($update->message->chat->id, $response);

        } else {
            $telegram_api->sendMessage($update->message->chat->id, 'Hi! Send me your location and see a magic :)');
        }

    }
}

// На каждое сообщение отвечаем