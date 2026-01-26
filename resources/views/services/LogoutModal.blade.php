<div id="logoutModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Logout</h3>
            <button type="button" onclick="document.getElementById('logoutModal').classList.add('hidden')"
                class="text-gray-400 hover:text-gray-500">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
        </div>
        <div class="mb-6">
            <p class="text-gray-600">Apakah anda yakin ingin mengakhiri sesi ini?</p>
        </div>
        <div class="flex justify-end gap-3">
            <button type="button" onclick="document.getElementById('logoutModal').classList.add('hidden')"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                Cancel
            </button>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
