<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExceptionOccured extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The body of the message.
     *
     * @var string
     */
    public $content;
    public $main_url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content,$main_url)
    {
        $this->content = $content;
        $this->main_url = $main_url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.exception')
                    ->with('content', $this->content)
                    ->with('url', $this->main_url);
    }
}
