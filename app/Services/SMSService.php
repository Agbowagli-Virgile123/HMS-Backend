<?php 
namespace App\Services;

use MessageBird\Client;
use MessageBird\Objects\Message;
class SMSService
{
    protected $messageBird;

    public function __construct()
    {
        $this->messageBird = new Client(env('MESSAGEBIRD_API_KEY'));
    }

    public function sendSMS($recipient, $message)
    {
        $msg = new Message();
        $msg->originator = env('MESSAGEBIRD_ORIGINATOR', 'Hospital');
        $msg->recipients = [$recipient];
        $msg->body = $message;

        return $this->messageBird->messages->create($msg);
    }
}
