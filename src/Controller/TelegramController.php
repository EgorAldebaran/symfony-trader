<?php  

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use TelegramBot\Api\Client;
use TelegramBot\Api\Exception as TelegramException;
use TelegramBot\Api\Types\Update as TelegramUpdate;

class TelegramController extends AbstractController
{
    public $token;
    
    public function __construct()
    {
        $this->token = "6535202048:AAE6RkxQTkfrLLQby5BmWB4UAPgQ5488C50";
    }
    
    #[Route('/telegram/send', name: 'telegram_send')]
    public function send(): JsonResponse
    {
        try {
            $bot = new Client($this->token);


            /// Handle /ping command
            $bot->command('ping', function ($message) use ($bot) {
                $bot->sendMessage($message->getChat()->getId(), 'pong!');
            });

            $bot->command('text', function (TelgramUpdate $update) use ($bot) {
                $message = $update->getMessage();
                $id = $message()->getChat()->getId();
                $bot->sendMessage($id, 'Your message: '. $message->getText());
            });

            dd($bot);

            /// handle text message
            $bot->on(function (TelegramUpdate $update) use ($bot) {
                $message = $update->getMessage();
                $id = $message()->getChat()->getId();
                $bot->sendMessage($id, 'Your message: ' . $message->getText());
            });

            dd($bot);
            $bot->run();
            
        } catch (\TelegramException $e) {
            $e->getMessage();
        }

        
        return new JsonResponse([
            'message' => 'ok'
        ]);
    }
}
