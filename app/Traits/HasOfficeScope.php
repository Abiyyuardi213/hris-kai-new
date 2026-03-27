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
        if (Auth::check() && !app()->runningInConsole() && !request()->routeIs('login') && !request()->routeIs('employee.login')) {
            if ((new static) instanceof \App\Models\User) {
                return;
            }

            /** @var \App\Models\User $user */
            $user = Auth::user();

            if (($user->role && in_array(strtolower($user->role->role_name), ['super admin', 'administrator', 'admin'])) || $user->kantor_id == 2) {
                return;
            }

            $officeId = $user->kantor_id;

            if (!$officeId) {
                $employee = $user->employee()->first();
                if ($employee && $employee->kantor_id) {
                    $officeId = $employee->kantor_id;
                }
            }

            if (!$officeId) {
                return;
            }

            static::addGlobalScope('office_access', function (Builder $builder) use ($officeId) {
                $instance = $builder->getModel();

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
