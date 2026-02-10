@extends('layouts.app')
@section('title', 'User')

@section('content')
    <div class="flex items-center justify-between space-y-2 mb-6">
        <h2 class="text-2xl font-bold tracking-tight">Manajemen Users</h2>
        <button onclick="document.getElementById('createUserModal').classList.remove('hidden')"
            class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-black text-white hover:bg-black/90 h-9 px-4 py-2">
            <i data-lucide="plus" class="mr-2 h-4 w-4"></i> Tambah User
        </button>
    </div>

    <div class="space-y-4">
        <!-- Filter Card -->
        <div class="bg-white p-4 rounded-xl border border-zinc-200 shadow-sm">
            <form action="{{ route('users.index') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[240px] relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama atau email..."
                        class="pl-10 h-10 w-full rounded-lg border border-zinc-300 py-2 text-sm placeholder:text-zinc-500 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                </div>

                <div class="w-full sm:w-48">
                    <select name="role_id" onchange="this.form.submit()"
                        class="h-10 w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                        <option value="">Semua Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->role_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full sm:w-40">
                    <select name="status" onchange="this.form.submit()"
                        class="h-10 w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Non-aktif</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="inline-flex h-10 items-center justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 transition-colors">
                        Filter
                    </button>
                    @if (request()->anyFilled(['search', 'role_id', 'status']))
                        <a href="{{ route('users.index') }}"
                            class="inline-flex h-10 items-center justify-center rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 transition-colors">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="rounded-xl border bg-white shadow-sm overflow-hidden">
            <div class="p-4 border-b border-zinc-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-zinc-900">Daftar User</h3>
                <p class="text-sm text-zinc-500">Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }}
                    dari {{ $users->total() }} user</p>
            </div>
            <div class="w-full overflow-x-auto">
                <table class="w-full caption-bottom text-sm text-left">
                    <thead class="[&_tr]:border-b">
                        <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                            <th class="h-10 px-4 align-middle font-medium text-muted-foreground">No</th>
                            <th class="h-10 px-4 align-middle font-medium text-muted-foreground">Foto</th>
                            <th class="h-10 px-4 align-middle font-medium text-muted-foreground">Nama</th>
                            <th class="h-10 px-4 align-middle font-medium text-muted-foreground">Email</th>
                            <th class="h-10 px-4 align-middle font-medium text-muted-foreground">Role</th>
                            <th class="h-10 px-4 align-middle font-medium text-muted-foreground text-center">Status</th>
                            <th class="h-10 px-4 align-middle font-medium text-muted-foreground text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="[&_tr:last-child]:border-0">
                        @forelse ($users as $user)
                            <tr class="border-b transition-colors hover:bg-zinc-50/50">
                                <td class="p-4 align-middle text-zinc-500">{{ $users->firstItem() + $loop->index }}</td>
                                <td class="p-4 align-middle text-center">
                                    @if ($user->foto)
                                        <img src="{{ asset('storage/' . $user->foto) }}" alt="Avatar"
                                            class="h-10 w-10 rounded-full object-cover border ring-2 ring-zinc-50">
                                    @else
                                        <div
                                            class="h-10 w-10 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-500 ring-2 ring-zinc-50">
                                            <i data-lucide="user" class="h-5 w-5"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="p-4 align-middle font-medium text-zinc-900">{{ $user->name }}</td>
                                <td class="p-4 align-middle text-zinc-500">{{ $user->email }}</td>
                                <td class="p-4 align-middle">
                                    <span
                                        class="inline-flex items-center rounded-md bg-zinc-100 px-2 py-1 text-xs font-medium text-zinc-600 ring-1 ring-inset ring-zinc-500/10">
                                        {{ $user->role ? $user->role->role_name : '-' }}
                                    </span>
                                </td>
                                <td class="p-4 align-middle text-center">
                                    <span
                                        class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 {{ $user->status ? 'border-transparent bg-green-500 text-white shadow-sm' : 'border-transparent bg-red-500 text-white shadow-sm' }}">
                                        {{ $user->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="p-4 align-middle text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button onclick="openEditUserModal({{ json_encode($user) }})"
                                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-all hover:bg-zinc-100 h-8 w-8 text-zinc-400 hover:text-zinc-950">
                                            <i data-lucide="edit-2" class="h-4 w-4"></i>
                                        </button>
                                        <button type="button" onclick="openDeleteUserModal('{{ $user->id }}')"
                                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-all hover:bg-red-50 h-8 w-8 text-zinc-400 hover:text-red-600">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center text-zinc-500">
                                    <div class="flex flex-col items-center justify-center space-y-3">
                                        <div class="p-4 rounded-full bg-zinc-50 border border-zinc-100">
                                            <i data-lucide="user-minus" class="h-8 w-8 text-zinc-300"></i>
                                        </div>
                                        <div class="text-center">
                                            <p class="font-medium text-zinc-900">User tidak ditemukan</p>
                                            <p class="text-sm mt-1">Coba sesuaikan kata kunci pencarian atau filter Anda.
                                            </p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="p-4 border-t border-zinc-100 bg-zinc-50/50">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Create User Modal -->
    <div id="createUserModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Tambah User Baru</h3>
                <button type="button" onclick="document.getElementById('createUserModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-500">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div class="grid gap-2">
                        <label for="name" class="text-sm font-medium leading-none">Nama</label>
                        <input type="text" id="name" name="name" required
                            class="flex h-10 w-full rounded-md border border-input bg-white px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                    </div>
                    <div class="grid gap-2">
                        <label for="email" class="text-sm font-medium leading-none">Email</label>
                        <input type="email" id="email" name="email" required
                            class="flex h-10 w-full rounded-md border border-input bg-white px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                    </div>
                    <div class="grid gap-2">
                        <label for="password" class="text-sm font-medium leading-none">Password</label>
                        <input type="password" id="password" name="password" required
                            class="flex h-10 w-full rounded-md border border-input bg-white px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                    </div>
                    <div class="grid gap-2">
                        <label for="role_id" class="text-sm font-medium leading-none">Role</label>
                        <select id="role_id" name="role_id" required
                            class="flex h-10 w-full rounded-md border border-input bg-white px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                            <option value="">Pilih Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" data-role-name="{{ $role->role_name }}">
                                    {{ $role->role_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid gap-2" id="office_container" style="display: none;">
                        <label for="kantor_id" class="text-sm font-medium leading-none">Kantor (Wajib untuk Admin
                            Daop)</label>
                        <select id="kantor_id" name="kantor_id"
                            class="flex h-10 w-full rounded-md border border-input bg-white px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                            <option value="">Pilih Kantor</option>
                            @foreach ($offices as $office)
                                <option value="{{ $office->id }}">{{ $office->office_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid gap-2">
                        <label for="foto" class="text-sm font-medium leading-none">Foto</label>
                        <input type="file" id="foto" accept="image/*" onchange="initCropper(this, 'create')"
                            class="flex h-10 w-full rounded-md border border-input bg-white px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        <input type="hidden" name="cropped_foto" id="create_cropped_foto">
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="status" name="status" value="1" checked
                            class="h-4 w-4 rounded border-gray-300 text-black focus:ring-black">
                        <label for="status" class="text-sm font-medium leading-none">Aktif</label>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('createUserModal').classList.add('hidden')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-black rounded-md hover:bg-black/90">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Edit User</h3>
                <button type="button" onclick="document.getElementById('editUserModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-500">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>
            <form id="editUserForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div class="grid gap-2">
                        <label for="edit_name" class="text-sm font-medium leading-none">Nama</label>
                        <input type="text" id="edit_name" name="name" required
                            class="flex h-10 w-full rounded-md border border-input bg-white px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                    </div>
                    <div class="grid gap-2">
                        <label for="edit_email" class="text-sm font-medium leading-none">Email</label>
                        <input type="email" id="edit_email" name="email" required
                            class="flex h-10 w-full rounded-md border border-input bg-white px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                    </div>
                    <div class="grid gap-2">
                        <label for="edit_password" class="text-sm font-medium leading-none">Password (Kosongkan jika tidak
                            diubah)</label>
                        <input type="password" id="edit_password" name="password"
                            class="flex h-10 w-full rounded-md border border-input bg-white px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                    </div>
                    <div class="grid gap-2">
                        <label for="edit_role_id" class="text-sm font-medium leading-none">Role</label>
                        <select id="edit_role_id" name="role_id" required
                            class="flex h-10 w-full rounded-md border border-input bg-white px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                            <option value="">Pilih Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" data-role-name="{{ $role->role_name }}">
                                    {{ $role->role_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid gap-2" id="edit_office_container" style="display: none;">
                        <label for="edit_kantor_id" class="text-sm font-medium leading-none">Kantor (Wajib untuk Admin
                            Daop)</label>
                        <select id="edit_kantor_id" name="kantor_id"
                            class="flex h-10 w-full rounded-md border border-input bg-white px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                            <option value="">Pilih Kantor</option>
                            @foreach ($offices as $office)
                                <option value="{{ $office->id }}">{{ $office->office_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid gap-2">
                        <label for="edit_foto" class="text-sm font-medium leading-none">Foto</label>
                        <input type="file" id="edit_foto" accept="image/*" onchange="initCropper(this, 'edit')"
                            class="flex h-10 w-full rounded-md border border-input bg-white px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        <input type="hidden" name="cropped_foto" id="edit_cropped_foto">
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="edit_status" name="status" value="1"
                            class="h-4 w-4 rounded border-gray-300 text-black focus:ring-black">
                        <label for="edit_status" class="text-sm font-medium leading-none">Aktif</label>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('editUserModal').classList.add('hidden')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-black rounded-md hover:bg-black/90">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete User Modal -->
    <div id="deleteUserModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus</h3>
                <button type="button" onclick="document.getElementById('deleteUserModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-500">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>
            <div class="mb-6">
                <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat
                    dibatalkan.</p>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('deleteUserModal').classList.add('hidden')"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    Batal
                </button>
                <form id="deleteUserForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Cropper Modal -->
    <div id="cropperModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/80 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl mx-4 p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Potong Foto Profile</h3>
                <button type="button" onclick="closeCropperModal()" class="text-gray-400 hover:text-gray-500">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>
            <div class="max-h-[60vh] overflow-hidden bg-zinc-100 rounded-md">
                <img id="cropperImage" src="" alt="Source Image" class="max-w-full block">
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeCropperModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    Batal
                </button>
                <button type="button" onclick="saveCroppedImage()"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    Potong & Simpan
                </button>
            </div>
        </div>
    </div>

    <script>
        let cropper = null;
        let currentMode = ''; // 'create' or 'edit'

        function initCropper(input, mode) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                currentMode = mode;
                reader.onload = function(e) {
                    const image = document.getElementById('cropperImage');
                    image.src = e.target.result;
                    document.getElementById('cropperModal').classList.remove('hidden');

                    if (cropper) {
                        cropper.destroy();
                    }

                    cropper = new Cropper(image, {
                        aspectRatio: 1,
                        viewMode: 2,
                        autoCropArea: 1,
                    });
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function closeCropperModal() {
            document.getElementById('cropperModal').classList.add('hidden');
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            // Reset file input
            const inputId = currentMode === 'create' ? 'foto' : 'edit_foto';
            document.getElementById(inputId).value = '';
        }

        function saveCroppedImage() {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                    width: 400,
                    height: 400,
                });

                const croppedDataUrl = canvas.toDataURL('image/jpeg');
                const hiddenInputId = currentMode === 'create' ? 'create_cropped_foto' : 'edit_cropped_foto';
                document.getElementById(hiddenInputId).value = croppedDataUrl;

                // Optional: Show preview logic could be added here

                document.getElementById('cropperModal').classList.add('hidden');
                cropper.destroy();
                cropper = null;
            }
        }

        const adminRoles = ['Super Admin', 'Administrator', 'Admin'];

        function toggleOffice(roleSelectId, containerId, officeSelectId) {
            const roleSelect = document.getElementById(roleSelectId);
            const container = document.getElementById(containerId);
            const officeSelect = document.getElementById(officeSelectId);

            if (!roleSelect || !container || !officeSelect) return;

            const selectedOption = roleSelect.options[roleSelect.selectedIndex];
            const roleName = selectedOption.getAttribute('data-role-name');

            if (roleName && !adminRoles.includes(roleName)) {
                container.style.display = 'grid'; // Match the 'grid' class of other fields
                if (roleName.toLowerCase().includes('admin daop') || roleName.toLowerCase().includes('admin regional')) {
                    officeSelect.required = true;
                } else {
                    officeSelect.required = false;
                }
            } else {
                container.style.display = 'none';
                officeSelect.value = '';
                officeSelect.required = false;
            }
        }

        // Initialize listeners
        document.getElementById('role_id').addEventListener('change', function() {
            toggleOffice('role_id', 'office_container', 'kantor_id');
        });

        document.getElementById('edit_role_id').addEventListener('change', function() {
            toggleOffice('edit_role_id', 'edit_office_container', 'edit_kantor_id');
        });

        function openEditUserModal(user) {
            document.getElementById('editUserForm').action = "{{ url('admin/users') }}/" + user.id;
            document.getElementById('edit_name').value = user.name;
            document.getElementById('edit_email').value = user.email;
            document.getElementById('edit_role_id').value = user.role_id;
            document.getElementById('edit_kantor_id').value = user.kantor_id || '';
            document.getElementById('edit_status').checked = user.status == 1;
            document.getElementById('edit_cropped_foto').value = ''; // Reset cropped data

            // Trigger office toggle logic
            toggleOffice('edit_role_id', 'edit_office_container', 'edit_kantor_id');

            document.getElementById('editUserModal').classList.remove('hidden');
        }

        function openDeleteUserModal(userId) {
            document.getElementById('deleteUserForm').action = "{{ url('admin/users') }}/" + userId;
            document.getElementById('deleteUserModal').classList.remove('hidden');
        }
    </script>
@endsection
