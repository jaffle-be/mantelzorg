<?php

namespace App;

use Illuminate\Mail\Mailer;
use Lang;

class UserMailer
{

    /**
     * @var Mailer
     */
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param User   $user
     * @param string $original
     */
    public function passwordGenerated(User $user, $original)
    {
        $data = ['user' => $user, 'original' => $original];

        $this->mailer->send('emails.auth.password', $data, function ($message) use ($user) {
            $message->to($user->email, $user->firstname . ' ' . $user->lastname)
                ->subject(Lang::get('email.registration.subject'));
        });
    }
}