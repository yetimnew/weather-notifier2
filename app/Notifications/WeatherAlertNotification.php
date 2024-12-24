<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class WeatherAlertNotification extends Notification
{
    use Queueable;

    public string $city;
    public float $precipitation;
    public float $uvIndex;
    public string $message;

    /**
     * Create a new notification instance.
     *
     * @param string $city
     * @param float $precipitation
     * @param float $uvIndex
     * @param string $message
     */
    public function __construct(string $city, float $precipitation, float $uvIndex, string $message)
    {
        $this->city = $city;
        $this->precipitation = $precipitation;
        $this->uvIndex = $uvIndex;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Weather Alert Notification for ' . $this->city)
            ->line('Hello ' . $notifiable->name . ',')
            ->line('A weather alert has been triggered for your city, ' . $this->city . '.')
            ->line($this->message)
            ->line('Precipitation Level: ' . $this->precipitation . 'mm')
            ->line('UV Index: ' . $this->uvIndex)
            ->action('View More Details', url('/dashboard'))
            ->line('Stay safe and thank you for using our service.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'city' => $this->city,
            'precipitation' => $this->precipitation,
            'uv_index' => $this->uvIndex,
            'message' => $this->message,
        ];
    }
}
