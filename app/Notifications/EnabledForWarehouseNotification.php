<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnabledForWarehouseNotification extends Notification
{
    use Queueable;

    private $merchOrderId = null;
    private $notificationCC = [];
    private $updatedBy = "";

    public function __construct($data)
    {
        if (isset($data['merch_order_id'])) {
            $this->merchOrderId = $data['merch_order_id'];
            $this->updatedBy = $data['name'] . "(" . $data['email'] . ")";
        }

        $emails = User::where('department', 'WH')->pluck('email')->toArray();
        $this->notificationCC = $emails;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->cc($this->notificationCC)
            ->from('noreply@sas.co.nz')
            ->bcc(explode(',', config('mail.notification_bcc')))
            ->subject("Factory Dashboard - A new order has been enabled for warehouse.")
            ->line("The order ID " . $this->merchOrderId . " has been enabled by " . $this->updatedBy . " for warehouse.")
            ->line('Thank you for using our application!');
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
            //
        ];
    }
}
