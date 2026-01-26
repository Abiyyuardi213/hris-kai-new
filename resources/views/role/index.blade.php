@extends('layouts.app')
@section('title', 'Role')

@section('content')
    <div class="flex items-center justify-between space-y-2 mb-6">
        <h2 class="text-2xl font-bold tracking-tight">Manajemen Peran</h2>
        <button onclick="document.getElementById('createRoleModal').classList.remove('hidden')"
            class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-black text-white hover:bg-black/90 h-9 px-4 py-2">
            <i data-lucide="plus" class="mr-2 h-4 w-4"></i> Tambah Peran
        </button>
    </div>

    <div class="rounded-md border bg-white">
        <div class="p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Daftar Peran</h3>
            <div class="relative w-full overflow-auto">
                <table class="w-full caption-bottom text-sm text-left">
                    <thead class="[&_tr]:border-b">
                        <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                            <th class="h-10 px-4 align-middle font-medium text-muted-foreground">No</th>
                            <th class="h-10 px-4 align-middle font-medium text-muted-foreground">Nama Peran</th>
                            <th class="h-10 px-4 align-middle font-medium text-muted-foreground">Deskripsi</th>
                            <th class="h-10 px-4 align-middle font-medium text-muted-foreground text-center">Status</th>
                            <th class="h-10 px-4 align-middle font-medium text-muted-foreground text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="[&_tr:last-child]:border-0">
                        @foreach ($roles as $index => $role)
                            <tr class="border-b transition-colors hover:bg-zinc-100/50">
                                <td class="p-4 align-middle">{{ $index + 1 }}</td>
                                <td class="p-4 align-middle font-medium">{{ $role->role_name }}</td>
                                <td class="p-4 align-middle">{{ $role->role_description }}</td>
                                <td class="p-4 align-middle text-center">
                                    <span
                                        class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 {{ $role->role_status ? 'border-transparent bg-green-500 text-white shadow hover:bg-green-600' : 'border-transparent bg-red-500 text-white shadow hover:bg-red-600' }}">
                                        {{ $role->role_status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="p-4 align-middle text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button onclick="openEditModal({{ json_encode($role) }})"
                                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8">
                                            <i data-lucide="edit-2" class="h-4 w-4"></i>
                                        </button>
                                        <button type="button"
                                            onclick="if(confirm('Apakah anda yakin ingin menghapus peran ini?')) { document.getElementById('delete-role-{{ $role->id }}').submit(); }"
                                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-red-100 hover:text-red-900 h-8 w-8 text-red-600">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </button>
                                        <form id="delete-role-{{ $role->id }}"
                                            action="{{ route('role.destroy', $role->id) }}" method="POST" class="hidden">
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

    <!-- Create Role Modal -->
    <div id="createRoleModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Tambah Peran Baru</h3>
                <button type="button" onclick="document.getElementById('createRoleModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-500">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>
            <form action="{{ route('role.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div class="grid gap-2">
                        <label for="role_name"
                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Nama
                            Peran</label>
                        <input type="text" id="role_name" name="role_name" required
                            class="flex h-10 w-full rounded-md border border-input bg-white px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                    </div>
                    <div class="grid gap-2">
                        <label for="role_description"
                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Deskripsi</label>
                        <textarea id="role_description" name="role_description" required
                            class="flex min-h-[80px] w-full rounded-md border border-input bg-white px-3 py-2 text-sm ring-offset-background placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"></textarea>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="role_status" name="role_status" value="1" checked
                            class="h-4 w-4 rounded border-gray-300 text-black focus:ring-black">
                        <label for="role_status"
                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Aktif</label>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('createRoleModal').classList.add('hidden')"
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

    <!-- Edit Role Modal -->
    <div id="editRoleModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Edit Peran</h3>
                <button type="button" onclick="document.getElementById('editRoleModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-500">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>
            <form id="editRoleForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div class="grid gap-2">
                        <label for="edit_role_name"
                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Nama
                            Peran</label>
                        <input type="text" id="edit_role_name" name="role_name" required
                            class="flex h-10 w-full rounded-md border border-input bg-white px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                    </div>
                    <div class="grid gap-2">
                        <label for="edit_role_description"
                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Deskripsi</label>
                        <textarea id="edit_role_description" name="role_description" required
                            class="flex min-h-[80px] w-full rounded-md border border-input bg-white px-3 py-2 text-sm ring-offset-background placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"></textarea>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="edit_role_status" name="role_status" value="1"
                            class="h-4 w-4 rounded border-gray-300 text-black focus:ring-black">
                        <label for="edit_role_status"
                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Aktif</label>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('editRoleModal').classList.add('hidden')"
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
        function openEditModal(role) {
            document.getElementById('editRoleForm').action = "{{ url('admin/role') }}/" + role.id;
            document.getElementById('edit_role_name').value = role.role_name;
            document.getElementById('edit_role_description').value = role.role_description;
            document.getElementById('edit_role_status').checked = role.role_status == 1;
            document.getElementById('editRoleModal').classList.remove('hidden');
        }
    </script>
@endsection
