<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class CardReviewReminder extends Notification
{
    use Queueable;

    public function __construct(
        protected int $dueCount = 0,
    ) {
    }

    public function via($notifiable): array
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        $body = $this->dueCount === 1
            ? 'You have 1 Card due for review.'
            : "You have {$this->dueCount} Cards due for review.";

        return (new WebPushMessage)
            ->title('The Card Dealer is waiting!')
            ->icon('/icons/icon-192.png')
            ->body($body)
            ->action('Go to Card Dealer', 'card_dealer')
            ->options([
                'TTL' => 3600,
            ]);
    }
}
