<?php

namespace App\Http\Controllers;

use App\Models\DailySafetyPatrol;
use App\Models\ValidationSafetyPatrol;
use Illuminate\Http\Request;

class ValidationSafetyPatrolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, DailySafetyPatrol $report)
    {
        $request->validate([
            'status' => 'required|in:valid,ditolak,revisi',
            'komentar' => 'nullable|string',
        ]);
        //dd(auth()->id());
    
        // Simpan riwayat validasi
        ValidationSafetyPatrol::create([
            'safety_patrol_id' => $report->id,
            'validator_id' => auth()->id(),
            'status' => $request->status,
            'komentar' => $request->komentar,
        ]);

        //dd($request->status);
    
        // Update status laporan
        $report->update([
            'status_validasi' => $request->status,
        ]);
    
        return back()->with('toast_success', 'Validasi berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ValidationSafetyPatrol $validationSafetyPatrol)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ValidationSafetyPatrol $validationSafetyPatrol)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ValidationSafetyPatrol $validationSafetyPatrol)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ValidationSafetyPatrol $validationSafetyPatrol)
    {
        //
    }
}
