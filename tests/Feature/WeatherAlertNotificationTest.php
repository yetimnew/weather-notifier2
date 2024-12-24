<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\WeatherAlertNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;

class WeatherAlertNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_sent_to_user()
    {
        Notification::fake();

        $user = User::factory()->create();

        $notification = new WeatherAlertNotification('Addis Ababa', 12.3, 8.0, 'Severe weather alert for your city.');
        Notification::send($user, $notification);

        Notification::assertSentTo(
            $user,
            WeatherAlertNotification::class,
            function ($notification, $channels) {
                return in_array('mail', $channels) && $notification->city === 'Addis Ababa';
            }
        );
    }

    public function test_email_is_sent_correctly()
    {
        Notification::fake();

        $user = User::factory()->create();

        $notification = new WeatherAlertNotification(
            'Addis Ababa',
            12.0, // Precipitation
            5.0,  // UV Index
            'This is a test alert message.'
        );

        // Send the notification
        Notification::send($user, $notification);

        // Assert the notification was sent
        Notification::assertSentTo(
            [$user],
            WeatherAlertNotification::class,
            function ($notification, $channels) use ($user) {
                return in_array('mail', $channels);
            }
        );
    }

    public function test_notification_load()
    {
        Notification::fake();

        $users = User::factory()->count(10)->create();

        foreach ($users as $user) {
            $notification = new WeatherAlertNotification('Addis Ababa', 12.3, 8.0, 'Severe weather alert for your city.');
            Notification::send($user, $notification);
        }

        foreach ($users as $user) {
            Notification::assertSentTo($user, WeatherAlertNotification::class);
        }

        Notification::assertCount(10);
    }

    // public function test_email_delivery_failure()
    // {
    //     Mail::fake();
    //     $user = User::factory()->create();

    //     Mail::shouldReceive('send')
    //         ->once()
    //         ->andThrow(new \Exception('Mail delivery failed'));

    //     Notification::send($user, new WeatherAlertNotification('Addis Ababa', 12.0, 7.0, 'Alert message'));

    //     Mail::assertNothingSent();
    // }
}
