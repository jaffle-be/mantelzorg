<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\BaseController;
use Illuminate\Foundation\Auth\ResetsPasswords;
class PasswordController extends BaseController
{
    protected $redirectTo = 'instrument';
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */
    use ResetsPasswords;
    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function resetEmailBuilder()
    {
        return function ($message) {
            $message->subject(\Lang::get('reminders.email.title'));
            $message->from('systeem@zichtopmantelzorg.be');
        };
    }

    
}