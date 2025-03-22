<!--suppress JSUnresolvedReference -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof Echo !== 'undefined') {
            Echo.private('users.{{ auth()->id() }}')
                .notification((notification) => {
                    Debug('notification', 'get', notification);
                    if (notification.refresh) {
                        Livewire.dispatch('refresh.' + notification.refresh);
                    }
                    if (notification.flash) {
                        Livewire.dispatch('flash', {
                            message: notification.flash,
                            link: notification.link
                        });
                    }
                });
            Debug('notification', 'init');
        } else {
            console.error('[notification] Echo is not initialized');
        }
    });
</script>
