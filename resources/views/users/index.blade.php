@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between space-y-2 mb-6">
        <h2 class="text-2xl font-bold tracking-tight">Manajemen Users</h2>
        <button onclick="document.getElementById('createUserModal').classList.remove('hidden')"
            class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-black text-white hover:bg-black/90 h-9 px-4 py-2">
            <i data-lucide="plus" class="mr-2 h-4 w-4"></i> Tambah User
        </button>
    </div>

    <div class="rounded-md border bg-white">
        <div class="p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Daftar User</h3>
            <div class="relative w-full overflow-auto">
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
                        @foreach ($users as $index => $user)
                            <tr class="border-b transition-colors hover:bg-zinc-100/50">
                                <td class="p-4 align-middle">{{ $index + 1 }}</td>
                                <td class="p-4 align-middle">
                                    @if ($user->foto)
                                        <img src="{{ asset('storage/' . $user->foto) }}" alt="Avatar"
                                            class="h-10 w-10 rounded-full object-cover border">
                                    @else
                                        <div
                                            class="h-10 w-10 rounded-full bg-zinc-200 flex items-center justify-center text-zinc-500">
                                            <i data-lucide="user" class="h-5 w-5"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="p-4 align-middle font-medium">{{ $user->name }}</td>
                                <td class="p-4 align-middle text-zinc-500">{{ $user->email }}</td>
                                <td class="p-4 align-middle">{{ $user->role ? $user->role->role_name : '-' }}</td>
                                <td class="p-4 align-middle text-center">
                                    <span
                                        class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 {{ $user->status ? 'border-transparent bg-green-500 text-white shadow hover:bg-green-600' : 'border-transparent bg-red-500 text-white shadow hover:bg-red-600' }}">
                                        {{ $user->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="p-4 align-middle text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button onclick="openEditUserModal({{ json_encode($user) }})"
                                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8">
                                            <i data-lucide="edit-2" class="h-4 w-4"></i>
                                        </button>
                                        <button type="button"
                                            onclick="if(confirm('Apakah anda yakin ingin menghapus user ini?')) { document.getElementById('delete-user-{{ $user->id }}').submit(); }"
                                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-red-100 hover:text-red-900 h-8 w-8 text-red-600">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </button>
                                        <form id="delete-user-{{ $user->id }}"
                                            action="{{ route('users.destroy', $user->id) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
                                <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid gap-2">
                        <label for="foto" class="text-sm font-medium leading-none">Foto</label>
                        <input type="file" id="foto" name="foto"
                            class="flex h-10 w-full rounded-md border border-input bg-white px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
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
                                <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid gap-2">
                        <label for="edit_foto" class="text-sm font-medium leading-none">Foto</label>
                        <input type="file" id="edit_foto" name="foto"
                            class="flex h-10 w-full rounded-md border border-input bg-white px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
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

    <script>
        function openEditUserModal(user) {
            document.getElementById('editUserForm').action = "{{ url('admin/users') }}/" + user.id;
            document.getElementById('edit_name').value = user.name;
            document.getElementById('edit_email').value = user.email;
            document.getElementById('edit_role_id').value = user.role_id;
            document.getElementById('edit_status').checked = user.status == 1;
            document.getElementById('editUserModal').classList.remove('hidden');
        }
    </script>
@endsection
