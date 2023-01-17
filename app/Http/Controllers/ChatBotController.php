<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Twilio\Rest\Client;

class ChatBotController extends Controller
{
    // This function is used to listen for incoming messages
    public function listenToReplies(Request $request) {
        $from = $request->input('WaId'); // This is the number that sending your bot a message.
        $body = $request->input('Body'); // This is the message you get from the number sending the message to the bot.
        if($body === "Hola" || $body === "hola"){
            $message = "Hola!! 👋\n";
            $message .= "Este es un Bot creado por Emiliano Perez Mendez, envia tu nombre para continuar\n";
            $this->sendWhatsAppMessage($message, $from);
        
        }elseif($body === "Juan" || $body === "juan" || $body === "Juan ignacio" || $body === "juan ignacio" || $body === "Juan Ignacio"){
            $message = "Si crees que soy un Junior 🎓, enviá Junior\n";
            $this->sendWhatsAppMessage($message, $from);
        
        }else if($body === "Laura" || $body === "laura"){
            $message = "Te amo!! ❤❤❤ Gracias por apoyarme siempre sos lo mejor que me paso en la vida!\n";
            $this->sendWhatsAppMessage($message, $from);
        
        }else if($body === "Junior" || $body === "junior"){
            $message = "Bueno, este es mi Chatbot 📱 y así podemos estar todo el día, espero que lo hayas disfrutado.\n";
            $message .= "Gracias por utilizar mi Chatbot! ✋ \n";
            $this->sendWhatsAppMessage($message, $from);
        
        }else{
            $message = "envia una opcion correcta\n";
            $this->sendWhatsAppMessage($message, $from);
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