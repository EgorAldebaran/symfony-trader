<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TraderController extends AbstractController
{
    public string $botKey;
    public string $telegramApi;
    public string $roomReceiveWebhook;
    public string $getUpdates;
    public string $getMe;
    public string $setWebhook;
    public string $getWebhookInfo;
    public string $killWebhook;

    public function __construct(
        private HttpClientInterface $client,
    ){
        $this->botKey = "6535202048:AAE6RkxQTkfrLLQby5BmWB4UAPgQ5488C50";
        $this->telegramApi = "https://api.telegram.org/bot".$this->botKey;
        $this->roomReceiveWebhook = "https://831e-92-47-139-91.ngrok-free.app/telegram/get-webhook";
        $this->getUpdates = $this->telegramApi . "/getUpdates";
        $this->getMe = $this->telegramApi . "/getMe". $this->roomReceiveWebhook;
        $this->setWebhook = $this->telegramApi . "/setWebhook?url=" . $this->roomReceiveWebhook;
        $this->killWebhook = $this->telegramApi . "/setWebhook?url=";
        $this->getWebhookInfo = $this->telegramApi . "/getWebhookInfo";
    }

    #[Route('/telegram/get-webhook', name: 'get-webhook')]
    public function getWebhookInfo(): JsonResponse
    {
        $response = $this->client->request(
            'GET',
            $this->getWebhookInfo,
        );

        $content = $response->getContent();
        $content = $response->toArray();
        dd($content);
    }

    #[Route('/telegram/set', name: 'set-webhook')]
    public function setWebhook(): JsonResponse
    {
        ///https://api.telegram.org/bot{my_bot_token}/setWebhook?url={url_to_send_updates_to}
        ///https://api.telegram.org/bot123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11/setWebhook?url=https://www.example.com

        $botKey = "6535202048:AAE6RkxQTkfrLLQby5BmWB4UAPgQ5488C50";
        $commandBot = "https://api.telegram.org/bot".$botKey."/getMe";
        $roomReceiveWebhook = "https://831e-92-47-139-91.ngrok-free.app/telegram/set-webhook";
        $setWebhook = "https://api.telegram.org/bot".$botKey."/setWebhook?url=".$roomReceiveWebhook;
        //dd($setWebhook);

        $response = $this->client->request(
            'GET',
            $setWebhook,
        );

        $content = $response->getContent();
        $content = $response->toArray();

        //dd($content);

        $ok = $content["ok"];
        $result = $content["result"];
        $description = $content["description"];
        
        return new JsonResponse([
            'message' => 'set webhook',
            'ok' => $ok,
            'result' => $result,
            'description' => $description,
        ]);
    }
    
    #[Route('/telegram/info', name: 'http_trader')]
    public function httpTrader(): Response
    {
        $botKey = "6535202048:AAE6RkxQTkfrLLQby5BmWB4UAPgQ5488C50";
        $commandBot = "https://api.telegram.org/bot".$botKey."/getMe";
        
        $response = $this->client->request(
            'GET',
            $commandBot,
        );

        $statusCode = $response->getStatusCode();
        //dd($statusCode);
        $contentType = $response->getHeaders()['content-type'][0];
        //dd($contentType);
        $content = $response->getContent();
        //dd($content);
        $content = $response->toArray();

        //dd($content["result"]["username"]);

        $cyberName = $content["result"]["username"];

        echo "Кибер имя в телеге - ". $cyberName;

        return $this->render('/trader/prototype.html.twig');
    }

    
    #[Route('/prototype', name: 'prototype')]
    public function prototype(): Response
    {
        $botKey = "6535202048:AAE6RkxQTkfrLLQby5BmWB4UAPgQ5488C50";
        $commandBot = "https://api.telegram.org/bot".$botKey."/getMe";
        
        $process = new Process(['curl', $commandBot]);
        $process->run();

        $json = $process->getOutput();
        $result = json_decode($json, true);
        $cyberName = $result["result"]["username"];

        /// executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        //echo $process->getOutput();

        echo "Твое кибер имя в телеграме - ".$cyberName;
        
        return $this->render('/trader/prototype.html.twig');
    }

    #[Route('/trader/quest', name: 'quest_trader')]
    public function quest(Request $request): Response
    {
        $message = $request->request->get('message');

        if ($message !== null) {
            
            $chat_id = -972785699;
            $http = $this->telegramApi . "/sendMessage?chat_id=". $chat_id . "&text=" . $message;

            $telegram = $this->client->request(
                'GET',
                $http,
            );

            $info = $telegram->getContent();
            $info = $telegram->toArray();

            dd($info["result"]["text"]);
            return $this->render('/trader/quest.html.twig', ['info' => 'Сообщение отправлено в телеграм!']);
        }

        return $this->render('/trader/quest.html.twig', ['info' => '']);
    }
    
    #[Route('/trader/nasdaq', name: 'nasdaq_trader')]
    public function nasdaq(): Response
    {
        return $this->render('/trader/nasdaq.html.twig');
    }

    #[Route('/trader/forex', name: 'forex_trader')]
    public function forex(): Response
    {
        return $this->render('/trader/forex.html.twig');
    }

    #[Route('/telegram/kill', name: 'telegram_delete')]
    public function deleteTelegram(): JsonResponse
    {
        $telegram = $this->client->request(
            'GET',
            $this->killWebhook,
        );

        $info = $telegram->toArray();

        $ok = $info["ok"];
        $result = $info["result"];
        $description = $info["description"];

        return new JsonResponse([
            'message' => 'kill webhook',
            'ok' => $ok,
            'result' => $result,
            'description' => $description,
        ]);
    }

    #[Route('/telegram/get', name: 'telegram_get')]
    public function getTelegram(): JsonResponse
    {
        $telegram = $this->client->request(
            'GET',
            $this->getUpdates,
        );

        $info = $telegram->getContent();
        $info = $telegram->toArray();

        /// я создал чат
        /// и закастил туда команду /join
        /// бота - теперь мне нужно вытащить айди бота
        
        $id_contr_quant_bot = $info["result"][0]["my_chat_member"]["chat"]["id"];

        return new JsonResponse([
            'contr_quant_bot id' => $id_contr_quant_bot,
        ]);
    }

    #[Route('/telegram/message-send', name: 'send-message')]
    public function sendMessage(): JsonResponse
    {
        $chat_id = -972785699;
        $message = "one two three four";
        $message = urlencode($message);

        $http = $this->telegramApi . "/sendMessage?chat_id=". $chat_id . "&text=". $message;
        ///dd($http);

        $telegram = $this->client->request(
            'GET',
            $http,
        );

        $info = $telegram->toArray();

        return new JsonResponse([
            'message' => 'Отправка сообщения',
            'text message' => $message
        ]);
    }
}
