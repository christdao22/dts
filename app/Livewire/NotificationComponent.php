<?php

namespace App\Livewire;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationComponent extends Component
{
    use WithPagination;

    public $totalUnread = 0;
    public $perPage = 10;
    public $userId;

    protected $listeners = ['load-more' => 'loadMore'];

    public function loadMore()
    {
        $this->perPage += 10;
    }


    public function mount() {
        $this->userId = auth()->user()->id;
        $this->updateNotifications();
    }

    public function updateNotifications() {
        $this->totalUnread = DB::table('notifications')->whereNull('read_at')->where('id', $this->userId)->count('id');

        return DB::table('notifications')
            ->where('user_id', $this->userId)
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

                $this->totalUnread = DB::table('notifications')->whereNull('read_at')->where('id', $this->userId)->count('id');
            }
        }
    }

    public function render()
    {
       return view('livewire.notification-component', [
            'notifications' => $this->updateNotifications(),
            'totalUnread' => $this->totalUnread,
        ]);
    }
}
