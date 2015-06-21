<?php namespace App\Notifications;

use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;
use Illuminate\Translation\Translator;
use App\User;

class EmailNotifier
{

    /**
     * @var Mailer
     */
    protected $mail;

    /**
     * @var Translator
     */
    protected $lang;

    public function __construct(Mailer $mailer, Translator $lang)
    {
        $this->mail = $mailer;
        $this->lang = $lang;
    }

    public function notify(User $user, $view, array $data)
    {
        $subject = $this->lang->get('notifications.' . $view . '.subject');

        $view = 'emails.notifications.' . $view;

        $this->mail->send($view, $data, function (Message $message) use ($user, $subject) {
            $message->to($user->getAttribute('email'));

            $message->subject($subject);
        });
    }
}