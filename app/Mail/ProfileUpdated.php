<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\UserProfile;

class ProfileUpdated extends Mailable
{
    use Queueable;
    public $oldProfile;
    public $newProfile;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(UserProfile $oldProfile, UserProfile $newProfile)
    {
        $this->oldProfile = $oldProfile;
        $this->newProfile = $newProfile;
        $this->subject("Обновлен профиль с id: ".$oldProfile->id);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.profileUpdated');
    }
}
