<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait HasOfficeScope
{
    /**
     * Boot the trait and apply the global scope.
     */
    public static function bootHasOfficeScope()
    {
        // Check if user is logged in and not running in console (unless testing)
        // Check if user is logged in and not running in console (unless testing)
        // Also ensure we are NOT on the login page/route, as this would prevent finding the user to login.
        if (Auth::check() && !app()->runningInConsole() && !request()->routeIs('login') && !request()->routeIs('employee.login')) {
            // Prevent applying scope to the User model itself to avoid recursion during Auth checks or relationship loading.
            if ((new static) instanceof \App\Models\User) {
                return;
            }

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // 0. BYPASS: Jika role adalah 'Super Admin', 'Administrator', atau 'Admin',
            // ATAU jika user tidak memiliki assigned kantor (kantor_id null), anggap sebagai admin pusat.
            if ($user->role && in_array($user->role->role_name, ['Super Admin', 'Administrator', 'Admin'])) {
                return;
            }

            // Dapatkan office ID dari user langsung atau dari pegawai yang terhubung
            $officeId = $user->kantor_id;

            // Fallback ke data pegawai jika user.kantor_id kosong (untuk backward compatibility)
            if (!$officeId) {
                $employee = $user->employee()->first();
                if ($employee && $employee->kantor_id) {
                    $officeId = $employee->kantor_id;
                }
            }

            // Jika masih null, berarti user ini adalah admin pusat (sesuai request: "tidak memiliki kantor_id berarti dia admin")
            // Maka jangan batasi query (return early)
            if (!$officeId) {
                return;
            }

            // Jika ada office ID, terapkan scope
            static::addGlobalScope('office_access', function (Builder $builder) use ($officeId) {
                $instance = $builder->getModel();

                // 1. Check for custom scope method in the model
                if (method_exists($instance, 'applyOfficeScope')) {
                    $instance->applyOfficeScope($builder, $officeId);
                    return;
                }

                // 2. Default logic based on defined properties
                // Property: $officeScopeType ('direct', 'relation', 'has_many_relation')
                $type = $instance->officeScopeType ?? 'direct';

                if ($type === 'direct') {
                    // Direct column match (e.g., kantor_id, office_id)
                    $column = $instance->officeScopeColumn ?? 'kantor_id';
                    $builder->where($instance->getTable() . '.' . $column, $officeId);
                } elseif ($type === 'relation') {
                    // BelongsTo relationship (e.g., belongs to a Pegawai who belongs to a Kantor)
                    $relation = $instance->officeScopeRelation ?? 'pegawai';
                    $builder->whereHas($relation, function ($q) use ($officeId) {
                        $q->where('kantor_id', $officeId);
                    });
                } elseif ($type === 'has_many_relation') {
                    // HasMany relationship (e.g., Divisi has many Employees)
                    // logic: Show item if it has at least one employee in the user's office
                    $relation = $instance->officeScopeRelation ?? 'employees';
                    $builder->whereHas($relation, function ($q) use ($officeId) {
                        $q->where('kantor_id', $officeId);
                    });
                }
            });
        }
    }
}
