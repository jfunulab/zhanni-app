<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Support\NumericCodeGenerator;

class EmailVerificationNotification extends Notification
{
    use Queueable;

    /**
     * @var NumericCodeGenerator
     */
    private NumericCodeGenerator $numericCodeGenerator;

    /**
     * Create a new notification instance.
     *
     * @param NumericCodeGenerator $numericCodeGenerator
     */
    public function __construct(NumericCodeGenerator $numericCodeGenerator)
    {
        $this->numericCodeGenerator = $numericCodeGenerator;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $code = $this->numericCodeGenerator->execute();

        $notifiable->update([
            'email_verification_code' => $code,
            'verification_code_expires_at' => now()->addMinutes(60)
        ]);

        return (new MailMessage)
            ->subject('Account verification')
            ->line('Verify your account with code below:')
            ->line("Verification code: $code")
            ->line("Expires in 60 minutes")
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
