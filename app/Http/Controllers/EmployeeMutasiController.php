<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MutasiPegawai;
use App\Models\Pegawai;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class EmployeeMutasiController extends Controller
{
    public function index()
    {
        $employee = Auth::guard('employee')->user();

        $mutations = MutasiPegawai::where('employee_id', $employee->id)
            ->with(['fromDivision', 'toDivision', 'fromPosition', 'toPosition', 'fromOffice', 'toOffice'])
            ->latest('mutation_date')
            ->paginate(10);

        return view('employee.mutations.index', compact('employee', 'mutations'));
    }

    public function show($id)
    {
        $employee = Auth::guard('employee')->user();

        $mutation = MutasiPegawai::where('employee_id', $employee->id)
            ->with(['fromDivision', 'toDivision', 'fromPosition', 'toPosition', 'fromOffice', 'toOffice'])
            ->findOrFail($id);

        return view('employee.mutations.show', compact('employee', 'mutation'));
    }

    public function print($id)
    {
        $employee = Auth::guard('employee')->user();
        $mutation = MutasiPegawai::where('employee_id', $employee->id)
            ->with(['employee', 'fromOffice', 'toOffice', 'toDivision', 'toPosition'])
            ->findOrFail($id);

        $months = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];
        $monthRoman = $months[date('n', strtotime($mutation->mutation_date))];

        if ($mutation->mutation_code && str_starts_with($mutation->mutation_code, 'MUT-')) {
            $sequence = substr($mutation->mutation_code, 4);
        } elseif (!$mutation->mutation_code) {
            $sequence = str_pad($mutation->id, 3, '0', STR_PAD_LEFT);
        } else {
            $parts = explode('/', $mutation->mutation_code);
            $sequence = $parts[0];
        }

        // Logic specific for Employee Print:
        // Use ToOffice (Destination) as primary context, fallback to FromOffice
        $targetOffice = $mutation->toOffice ?? $mutation->fromOffice;
        $officeName = $targetOffice->office_name ?? '';

        $officeCode = 'KP';
        $signerCode = 'DZ';

        $officeNameLower = strtolower($officeName);

        if ($officeNameLower) {
            if (str_contains($officeNameLower, 'daop 1')) {
                $officeCode = 'D1';
                $signerCode = 'VP';
            } elseif (str_contains($officeNameLower, 'daop 2')) {
                $officeCode = 'D2';
                $signerCode = 'VP';
            } elseif (str_contains($officeNameLower, 'daop 3')) {
                $officeCode = 'D3';
                $signerCode = 'VP';
            } elseif (str_contains($officeNameLower, 'daop 4')) {
                $officeCode = 'D4';
                $signerCode = 'VP';
            } elseif (str_contains($officeNameLower, 'daop 5')) {
                $officeCode = 'D5';
                $signerCode = 'VP';
            } elseif (str_contains($officeNameLower, 'daop 6')) {
                $officeCode = 'D6';
                $signerCode = 'VP';
            } elseif (str_contains($officeNameLower, 'daop 7')) {
                $officeCode = 'D7';
                $signerCode = 'VP';
            } elseif (str_contains($officeNameLower, 'daop 8')) {
                $officeCode = 'D8';
                $signerCode = 'VP';
            } elseif (str_contains($officeNameLower, 'daop 9')) {
                $officeCode = 'D9';
                $signerCode = 'VP';
            } elseif (str_contains($officeNameLower, 'divre iii')) {
                $officeCode = 'DV3';
                $signerCode = 'DV';
            } elseif (str_contains($officeNameLower, 'divre ii')) {
                $officeCode = 'DV2';
                $signerCode = 'DV';
            } elseif (str_contains($officeNameLower, 'divre iv')) {
                $officeCode = 'DV4';
                $signerCode = 'DV';
            } elseif (str_contains($officeNameLower, 'divre i')) {
                $officeCode = 'DV1';
                $signerCode = 'DV';
            } elseif (str_contains($officeNameLower, 'lrt')) {
                $officeCode = 'LRT';
                $signerCode = 'VP';
            } elseif (str_contains($officeNameLower, 'pusat')) {
                $officeCode = 'KP';
                $signerCode = 'DZ';
            }
        }

        // Find VP
        $vp = null;
        if (($signerCode == 'VP' || $signerCode == 'DV') && $targetOffice) {
            // Find VP of the office
            $vp = Pegawai::where('kantor_id', $targetOffice->id)
                ->whereHas('jabatan', function ($query) {
                    // Check common VP naming conventions
                    $query->where('name', 'like', '%Vice President%')
                        ->orWhere('name', 'like', '%Kepala Divisi Regional%');
                })
                ->first();
        }

        return view('admin.mutations.print', compact('mutation', 'vp', 'monthRoman', 'sequence', 'officeCode', 'signerCode', 'officeName'));
    }
}
