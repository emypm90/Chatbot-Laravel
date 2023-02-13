<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Twilio\Rest\Client;

class ChatBotController extends Controller
{
    public function listenToReplies(Request $request)
    {
        $from = $request->input('WaId');
        $body = $request->input('Body');

        $client = new \GuzzleHttp\Client();
        try {
            if($body === "Hola" || $body === "hola"){
                $message = "Hola!! 👋\n";
                $message .= "Este es un Bot creado por Emiliano Perez Mendez, envia un usuario de Github para continuar\n";
                $this->sendWhatsAppMessage($message, $from);
            
            }else if ($body !== "Hola" || $body !== "hola" ) {
                $response = $client->request('GET', "https://api.github.com/users/$body");
                $githubResponse = json_decode($response->getBody());
                if($response->getStatusCode() == 200){
                    $message = "*Nombre:* $githubResponse->name\n";
                    $message .= "*Bio:* $githubResponse->bio\n";
                    $message .= "*Nacionalidad:* $githubResponse->location\n";
                    $message .= "*Cantidad de Repos:* $githubResponse->public_repos\n";
                    $message .= "*Seguidores:* $githubResponse->followers devs\n";
                    $message .= "*Seguidos:* $githubResponse->following devs\n";
                    $message .= "*URL:* $githubResponse->html_url\n";
                        $this->sendWhatsAppMessage($message, $from);
                } else {
                $this->sendWhatsAppMessage($githubResponse->message, $from);
                }
            }

        } catch (RequestException) {
            $this->sendWhatsAppMessage("Lo siento! no encontramos a $body. Enviá un usuario correcto", $from);
        }
        return;
    }

    // This function is used to send the message we complied in the
    public function sendWhatsAppMessage(string $message, string $recipient)
    {
        $twilio_whatsapp_number = getenv('TWILIO_WHATSAPP_NUMBER');
        $account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $client = new Client($account_sid, $auth_token);
        return $client->messages->create("whatsapp:$recipient", 
            array(
                'from' => "whatsapp:$twilio_whatsapp_number", 
                'body' => $message
            ));
    }
}