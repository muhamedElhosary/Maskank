<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Ichtrojan\Otp\Otp;


class ResetPasswordNotification extends Notification
{
    use Queueable;
    public $message;
    public $subject;
    public $fromEmail;
    public $mailer;
    private $otp;


    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        $this->message = 'use the below code for Reset password verification process';
        $this->subject = 'Reset Password verification needed';
        $this->fromEmail = 'test@gmail.com';
        $this->mailer = 'smtp';
        $this->otp = new Otp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        try {
            $otp = $this->otp->generate($notifiable->email,'numeric',6,60);
           } catch (\Exception $e) {
            // Handle OTP generation error
            throw new \Exception('Unable to generate OTP: ' . $e->getMessage());
        }
        return (new MailMessage)
        ->mailer('smtp')
        ->subject($this->subject)
        ->greeting('hello '.$notifiable->username)
        ->line($this->message)
        ->line('code : ' .$otp->token);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
