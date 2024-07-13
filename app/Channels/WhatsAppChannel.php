<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class WhatsAppChannel
{
    public function send($notifiable, Notification $notification)
    {
        try {
            $messageData = $notification->toWhatsApp($notifiable);
          
            Log::alert($messageData->parameters['messageContent']);

            $to = $notifiable->routeNotificationFor('whatsapp');
            $from = config('services.twilio.whatsapp_from');

            $twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));
            // Log::alert('here in channel send');
            $twilio->messages->create('whatsapp:'.$to, [
                "from" => 'whatsapp:'.$from,               
                 "body" => $messageData->parameters['messageContent'],
                // "mediaUrl" => [$messageData->pdfUrl]
            ]);
            return true;
        } catch (TwilioException $e) {
            // Log the exception or handle it as needed
            Log::error('Error sending WhatsApp WhatsAppChannel class : ' . $e->getMessage());
            return false;
        }
    }
}
