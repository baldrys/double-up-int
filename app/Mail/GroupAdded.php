<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\UserGroup;

class GroupAdded extends Mailable
{
    use Queueable, SerializesModels;

    public $group;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(UserGroup $group)
    {
        $this->group = $group;
        $this->subject("Добавлена новая группа - ".$group->name);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.groupAdded');
    }
}
