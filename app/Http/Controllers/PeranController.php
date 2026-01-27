<?php

namespace App\Http\Controllers;

use App\Models\Peran;
use Illuminate\Http\Request;

class PeranController extends Controller
{
    public function index()
    {
        $roles = Peran::orderBy('created_at', 'asc')->get();
        return view('role.index', compact('roles'));
    }

    public function create()
    {
        return view('role.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string|max:255',
            'role_description' => 'nullable|string',
            'role_status' => 'required|boolean',
        ]);

        Peran::createRole($request->all());

        return redirect()->route('role.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $role = Peran::findOrFail($id);
        return view('role.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'role_name' => 'required|string|max:255',
            'role_description' => 'nullable|string',
            'role_status' => 'required|boolean',
        ]);

        $role = Peran::findOrFail($id);
        $role->updateRole($request->all());

        return redirect()->route('role.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $role = Peran::findOrFail($id);
        $role->deleteRole();

        return redirect()->route('role.index')->with('success', 'Role berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        try {
            $role = Peran::findOrFail($id);
            $role->toggleStatus();

            return response()->json([
                'success' => true,
                'message' => 'Status role berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status.'
            ], 500);
        }
    }

    public function permissions($id)
    {
        $role = Peran::with('permissions')->findOrFail($id);
        $permissions = \App\Models\Permission::all()->groupBy('module');
        return view('role.permissions', compact('role', 'permissions'));
    }

    public function updatePermissions(Request $request, $id)
    {
        $role = Peran::findOrFail($id);
        $role->permissions()->sync($request->permissions);

        return redirect()->route('role.index')->with('success', 'Hak akses role berhasil diperbarui.');
    }
}
