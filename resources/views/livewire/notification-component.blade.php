<div class="notif-panel dropdown d-inline-block user-dropdown" >
    <button type="button" id="notifButton" wire:click="updateNotifications" class="btn header-item waves-effect text-size-50 position-relative">
        <i class="ri-notification-2-line h2 text-white"> </i>
        <span class="position-absolute translate-middle badge rounded-pill bg-danger notif-indicator {{ $totalUnread == 0? 'd-none':'' }}" style="top: 23px !important; left: 35px !important;">
            <span id="totalUnread">{{ $totalUnread }}</span>
            <span class="visually-hidden">unread messages</span>
        </span>
    </button>
    <div class="dropdown-menu dropdown-menu-end w-100 h-100 overflow-auto" style="min-height: 580px;" wire:ignore.self>
        <h2 class="dropdown-header w-100 mb-2 fw-bold">Notification</h2>
        @forelse ($notifications as $notification)
        <div class="dropdown-divider mt-0 mb-0"></div>
        <a wire:click="readNotification({{ $notification->id }}, '{{ $notification->read_at }}')" class="dropdown-item notif-item d-flex align-items-center justifty-content-between gap-3 pt-3 pb-3 {{ $notification->read_at == null? 'bg-light' : '' }}"
                data-bs-id="{{ $notification->document_detail_id }}" href="#">
            <div class="notif-icon border  {{ $notification->read_at == null? 'border-dark' : 'border-light' }} rounded-circle d-flex justify-content-center align-items-center">
                <i class="{{ $notification->read_at == null? 'ri-mail-check-fill' : 'ri-mail-check-line' }}   align-middle h5 mb-0"></i>
            </div>
            <div class="">
                <p class="mb-0 text-wrap text-dark {{ $notification->read_at == null? 'fw-bold' : '' }}">{{ $notification->action }}</p>
                <small class="text-muted">{{ time_ago($notification->created_at) }}</small>
            </div>
        </a>
        @empty
        <div class="dropdown-divider"></div>
        <p class="dropdown-item d-flex align-items-center justifty-content-between gap-3">No notification!</p>
        @endforelse

        @if ($notifications->hasMorePages())
            <div class="dropdown-divider"></div>
            <div class="loading text-center" wire:loading>
                Loading more notifications...
            </div>
            <div class="load-more" wire:loading.remove>
                <a wire:click="loadMore" href="#" class="text-center m-auto d-block">
                    Load More
                </a>
            </div>
        @endif
    </div>

    <script>
    document.addEventListener('livewire:load', function () {
        window.onscroll = function() {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                Livewire.emit('load-more');
            }
        };
    });
</script>
</div>

