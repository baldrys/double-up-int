<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserUpdated extends Mailable
{
    use Queueable;
    public $admin;
    public $newUser;
    public $oldUser;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($admin, $oldUser, $newUser)
    {
       $this->admin = $admin;
       $this->newUser = $newUser;
       $this->oldUser = $oldUser;
       $this->subject("Обновлены данные пользователя с id:".$oldUser->id);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.userUpdated');
    }
}
