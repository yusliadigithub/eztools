<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyAccount extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $merchantid;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $merchantid)
    {
        $this->email = $email;
        $this->merchantid = $merchantid;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.verifyAccount')->with(['email'=>$this->email, 'merchantid'=>$this->merchantid]);
    }
}
