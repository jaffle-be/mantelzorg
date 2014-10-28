<?php


use Illuminate\Mail\Mailer;

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
        $this->mailer->send('emails.auth.password', compact(array('original', 'user')), function ($message) use ($user)
        {
            $message->to($user->email, $user->firstname . ' ' . $user->lastname)
                ->subject(Lang::get('email.registration.subject'));
        });
    }

} 