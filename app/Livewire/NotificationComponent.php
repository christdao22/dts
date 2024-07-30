<?php

namespace App\Livewire;

use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationComponent extends Component
{
    use WithPagination;

    public $totalUnread = 0;
    public $perPage = 10;
    public $terminalId;

    protected $listeners = ['load-more' => 'loadMore'];

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function mount() {
        $this->terminalId = auth()->user()->terminal->id;
    }

    public function updateNotifications() {
        $this->totalUnread = $this->getTotalUnread();

        return DB::table('notifications')
            ->where('terminal_id', $this->terminalId)
            ->orderBy('created_at', 'desc')
            ->cursorPaginate($this->perPage);
    }

    public function readNotification($id, $read_at) {
        if (empty($read_at)) {
            $notification = Notification::find($id);

            if ($notification) {
                $notification->update(['read_at' => now()->startOfDay()->format('Y-m-d H:i:s')]);

                $notificationItem = DB::table('notifications')->where('id', $id)->first();
                if ($notificationItem) {
                    $notificationItem->read_at = $notification->read_at;
                }
            }
        }
    }

    public function markAllAsRead() {
        DB::table('notifications')->where('terminal_id', $this->terminalId)->update(['read_at' => now()->startOfDay()->format('Y-m-d H:i:s')]);
        $this->totalUnread = $this->getTotalUnread();
    }

    public function getTotalUnread() {
        return DB::table('notifications')->whereNull('read_at')->where('terminal_id', $this->terminalId)->count('id');
    }

    public function render()
    {
       return view('livewire.notification-component', [
            'notifications' => $this->updateNotifications(),
            'totalUnread' => $this->totalUnread,
        ]);
    }
}
