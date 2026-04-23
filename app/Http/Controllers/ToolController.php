<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\Request;

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
        } else if ($request->sort === 'menipis'){
            $query->whereRaw('stock <= stock_minimum * 0.1'); // default terbaru
        }

        $tools = $query->paginate(10)->withQueryString();

        $datas = Tool::all();

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
        Tool::create($data);
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
}
