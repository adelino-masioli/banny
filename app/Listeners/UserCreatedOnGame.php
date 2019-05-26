<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\UserRegistered;
use App\Http\Helpers;
use App\User;

class UserCreatedOnGame
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        $user  = $event->game;
        $email = $user['cellphone'].Helpers::define_domain();

        $verifyUser = User::where('email', $email)->count();

        if($verifyUser == 0){
            User::create([
                'name'        => $user['name'],
                'email'       => $email,
                'cellphone '  => $user['cellphone'],
                'password'    => bcrypt($user['cellphone']),
                'type'        => 'CUSTOMER',
                'status'      => 1,
            ]);
        }
    }
}
