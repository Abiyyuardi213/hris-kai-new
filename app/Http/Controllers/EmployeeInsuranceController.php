<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class EmployeeInsuranceController extends Controller
{
    public function index()
    {
        $employee = Auth::guard('employee')->user();
        
        // Mocking premium details for the view, these could come from Jabatan/Settings
        $insuranceData = [
            'plan_name' => 'MyCare Ultimate',
            'yearly_premium' => 1593000,
            'benefits' => [
                'Rawat Inap',
                'Hospital Income (Rp 500,000)',
                'Hospital Cash Plan (Rp 500,000)',
                'Ambulans (Rp 1,000,000)',
                'Evakuasi & Repatriasi Medis'
            ],
            'card_number' => 'MI-' . str_pad($employee->id, 8, '0', STR_PAD_LEFT),
            'effective_date' => $employee->join_date ?? now()->subMonths(6)->format('Y-m-d'),
        ];

        return view('employee.insurance.index', compact('employee', 'insuranceData'));
    }

    public function print()
    {
        $employee = Auth::guard('employee')->user();
        
        $insuranceData = [
            'plan_name' => 'MyCare Ultimate',
            'yearly_premium' => 1593000,
            'card_number' => 'MI-' . str_pad($employee->id, 8, '0', STR_PAD_LEFT),
            'effective_date' => $employee->join_date ?? now()->subMonths(6)->format('Y-m-d'),
        ];

        $pdf = Pdf::loadView('employee.insurance.print', compact('employee', 'insuranceData'))
            ->setPaper('a4', 'portrait');
            
        return $pdf->stream('Sertifikat_Inhealth_' . $employee->nip . '.pdf');
    }
}
