<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = 'levanhung090920@gmail.com';
        $subject = 'this is demo';
        $name = 'vanhung';

        $headerData = [
          'category' => 'category',
          'unique_args'  =>[
              'variable_1'=>'abc'
          ]
        ];

        $header = $this->asString($headerData);

        $this->withSwiftMessage(function ($message) use ($header) {
            $message->getHeaders()
                ->addTextHeader('X-SMTPAPI', $header);
        });

        return $this->view('test')
            ->from($address,$name)
            ->cc($address,$name)
            ->bcc($address,$name)
            ->replyTo($address,$name)
            ->subject($subject)
            ->with(['data'=>$this->data]);
    }

    private function asString($data)
    {
        $json = $this->asJSON($data);

        return wordwrap($json,76,"\n   ");
    }

    private function asJSON($data)
    {
        $json = json_encode($data);
        $json = preg_replace('/(["\]}])([,:])(["\[{])/', '$1$2 $3', $json);
        return $json;
    }
}
