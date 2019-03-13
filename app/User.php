<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Queue;
use App\Support\Enums\UserRole;
use App\Jobs\SendEmailJob;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token', 'banned', 'role',
    ];

    public function groups()
    {
        return $this->belongsToMany('App\UserGroup', 'user_groups', 'user_id', 'group_id');
    }

    /**
     * Генерация уникального токена
     *
     * @return void
     */
    public function rollApiKey()
    {
        do {
            $this->api_token = str_random(30);
        } while ($this->where('api_token', $this->api_token)->exists());
        $this->save();
    }

    /**
     * Оповещение всех админов
     *
     * @return void
     */
    public static function notifyAllAdmins($messageModel)
    {
        $admins = User::where('role', UserRole::Admin)->get();
        foreach ($admins as $admin) {
            $email = $admin->email;
            Queue::push(new SendEmailJob($email, $messageModel));
        }
    }
}
