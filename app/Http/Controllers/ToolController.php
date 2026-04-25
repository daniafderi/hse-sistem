<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class ToolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tool::query();

        // ============================
        // 2. Pencarian
        // ============================
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // ============================
        // 3. Sorting (Urutkan)
        // terbaru = desc, terlama = asc
        // ============================
        if ($request->sort === 'aman') {
            $query->whereRaw('stock > stock_minimum * 0.1');
        } else if ($request->sort === 'menipis') {
            $query->whereRaw('stock <= stock_minimum * 0.1'); // default terbaru
        }

        if (Gate::allows('isSupervisor')) {
            $tools = $query->paginate(10)->withQueryString();
        } else {
            $tools = $query->where('validation', 'valid')->paginate(10)->withQueryString();

        }

        $datas = $query->get();

        //dd($datas);

        return view('pages.tool.index', compact('tools', 'datas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.tool.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'stock_minimum' => 'required|integer|min:0'
        ]);
        Tool::create($request->only(['name', 'stock', 'stock_minimum']) + [
        'validation' => 'menunggu'
    ]);

    $notif = Notification::create([
            'type' => 'apd_validate',
            'title' => 'APD Baru ditambahkan',
            'message' => 'APD baru telah ditambahkan ke sistem',
            'notifiable_id' => $data->id,
            'notifiable_type' => Tool::class,
            'created_by' => auth()->id()
        ]);

        $users = User::whereIn('role', ['HSE Kantor', 'Supervisor'])->pluck('id');

        // kirim ke user tertentu
        $notif->users()->attach($users);
        return redirect()->route('tools.index')->with('success', 'Alat
           berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tool $tool)
    {
        $tool->load(['loanItems', 'stockTransaction']);

        return view('pages.tool.show', compact('tool'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tool $tool)
    {
        return view('pages.tool.edit', compact('tool'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tool $tool)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'stock_minimum' => 'required|integer|min:0'
        ]);
        $tool->update($data);
        return redirect()->route('tools.index')->with('success', 'Alat
           diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tool $tool)
    {
        //
    }

    public function validation(Request $request, Tool $tool)
    {
        $request->validate([
            'status' => 'required|in:valid,ditolak,revisi',
            'komentar' => 'nullable|string',
        ]);

        //dd($request->status);

        // Update status laporan
        $tool->update([
            'validation' => $request->status,
        ]);

        $notif = Notification::create([
            'type' => 'apd_validate',
            'title' => 'APD divalidasi',
            'message' => 'APD baru telah divalidasi',
            'notifiable_id' => $tool->id,
            'notifiable_type' => Tool::class,
            'created_by' => auth()->id()
        ]);

        $users = User::whereIn('role', ['HSE Kantor', 'Supervisor'])->pluck('id');

        // kirim ke user tertentu
        $notif->users()->attach($users);

        return back()->with('toast_success', 'Validasi berhasil disimpan.');
    }
}
