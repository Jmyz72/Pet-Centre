<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MerchantApplicationStatusNotification extends Notification
{
    use Queueable;

    /**
     * @param  string  $status  'approved' | 'rejected'
     * @param  string|null  $reason  Optional rejection reason
     * @param  int|null  $applicationId  For deep linking (optional)
     * @param  string|null  $applicationName  Business/Org name (optional)
     * @param  string|null  $role  Role applied for (optional)
     */
    public function __construct(
        public string $status,
        public ?string $reason = null,
        public ?int $applicationId = null,
        public ?string $applicationName = null,
        public ?string $role = null,
    ) {}

    /**
     * Send via email + database (web notification)
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Email content
     */
    public function toMail(object $notifiable): MailMessage
    {
        $isApproved = $this->status === 'approved';
        $name = $notifiable->name ?? 'there';
        $appName = $this->applicationName ? "{$this->applicationName} ({$this->role})" : 'your merchant application';

        $mail = (new MailMessage)
            ->subject('Merchant Application '.ucfirst($this->status))
            ->greeting('Hi '.$name.',')
            ->line($isApproved
                ? "Good news! {$appName} has been approved. You can now start managing your services as a {$this->role} on our platform."
                : "Unfortunately, {$appName} was rejected. You may review the reason below and reapply if possible."
            );

        if (! $isApproved && $this->reason) {
            $mail->line('Reason: '.$this->reason);
        }

        return $mail->action('View Application', route('merchant.application.submitted'));
    }

    /**
     * Database payload for in‑app notifications
     */
    public function toArray(object $notifiable): array
    {
        $appName = $this->applicationName ? "{$this->applicationName} ({$this->role})" : 'Your merchant application';

        return [
            'title' => 'Application '.ucfirst($this->status),
            'message' => $this->status === 'approved'
                ? "Congratulations! {$appName} has been approved. You can now start offering your {$this->role} services on Pet Centre."
                : "Unfortunately, {$appName} has been rejected".($this->reason ? (': '.$this->reason) : '.').' You may update your details and reapply if allowed.',
            'status' => $this->status,
            'reason' => $this->reason,
            'applicationId' => $this->applicationId,
            'action_url' => route('merchant.application.submitted'),
        ];
    }
}
