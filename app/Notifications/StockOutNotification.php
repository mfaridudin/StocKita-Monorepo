<?php

namespace App\Notifications;

use App\Models\Stock;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class StockOutNotification extends Notification
{
    use Queueable;

    protected $stock;

    public function __construct(Stock $stock)
    {
        $this->stock = $stock;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Stok Habis',
            'message' => $this->stock->product->name . ' - ' . $this->stock->warehouse->name,
            'url' => '/warehouse/' . $this->stock->warehouse_id,
        ];
    }

}