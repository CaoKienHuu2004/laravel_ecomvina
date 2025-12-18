<?php
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CanhBaoTonKhoNotification extends Notification
{
    use Queueable;

    protected $bienthe;
    protected $donhang;

    public function __construct($bienthe, $donhang)
    {
        $this->bienthe = $bienthe;
        $this->donhang = $donhang;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'canh_bao_ton_kho',
            'message' => 'Biến thể "' . $this->bienthe->sanpham->ten . '" sắp hết hàng',
            'soluong_con' => $this->bienthe->soluong,
            'donhang_id' => $this->donhang->id,
        ];
    }
}
