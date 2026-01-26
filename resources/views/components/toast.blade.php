@if (session('success') || session('error'))
    <div id="toast-notification"
        class="fixed top-5 right-5 z-50 transform transition-all duration-300 translate-x-full opacity-0">
        <div class="flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800"
            role="alert">
            <div
                class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg {{ session('success') ? 'text-green-500 bg-green-100 dark:bg-green-800 dark:text-green-200' : 'text-red-500 bg-red-100 dark:bg-red-800 dark:text-red-200' }}">
                @if (session('success'))
                    <i data-lucide="check" class="w-5 h-5"></i>
                @else
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                @endif
            </div>
            <div class="ms-3 text-sm font-normal">{{ session('success') ?? session('error') }}</div>
            <button type="button"
                class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                data-dismiss-target="#toast-notification" aria-label="Close" onclick="hideToast()">
                <span class="sr-only">Close</span>
                <i data-lucide="x" class="w-3 h-3"></i>
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('toast-notification');
            if (toast) {
                // Show toast
                setTimeout(() => {
                    toast.classList.remove('translate-x-full', 'opacity-0');
                }, 100);

                // Auto hide after 5 seconds
                setTimeout(() => {
                    hideToast();
                }, 5000);
            }
        });

        function hideToast() {
            const toast = document.getElementById('toast-notification');
            if (toast) {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }
    </script>
@endif
