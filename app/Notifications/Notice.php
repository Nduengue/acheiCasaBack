<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Notice extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $url;
    protected $type;
    protected $method;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $message, $url, $method = "GET", $type = "link")
    {
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
        $this->type = $type;
        $this->method = $method;
    }
    public function via()
    {
        return ['database'];
    }

    public function toDatabase()
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
            'method' => $this->method,
            'type' => $this->type,
        ];
    }

    // Método usado para API
    public function toArray()
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
            'type' => $this->type,
        ];
    }
}
