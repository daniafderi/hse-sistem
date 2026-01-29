<?php

namespace App\Http\Controllers;

use App\Http\Requests\SafetyBriefingRequest;
use App\Models\ImageSafetyBriefing;
use App\Models\SafetyBriefing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SafetyBriefingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SafetyBriefing::query();

        // ============================
        // 1. Filter Status
        // ============================
        if ($request->status && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        // ============================
        // 2. Pencarian
        // ============================
        if ($request->search) {
            $query->where('tempat', 'like', '%' . $request->search . '%');
        }

        // ============================
        // 3. Sorting (Urutkan)
        // terbaru = desc, terlama = asc
        // ============================
        if ($request->sort === 'terlama') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc'); // default terbaru
        }

        $datas = $query->paginate(10)->withQueryString();

        return view('pages.safety_briefing.index', compact(['datas']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.safety_briefing.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SafetyBriefingRequest $request)
    {
        $safetyBriefing = $request->validated();

        $briefing = SafetyBriefing::create($safetyBriefing);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('safety_briefing', 'public');

                $briefing->images()->create([
                    'image_url' => $path
                ]);
            }
        }

        return redirect()->route('safety-briefing.index')->with('success', 'berhasil menambah laporan baru');
    }

    /**
     * Display the specified resource.
     */
    public function show(SafetyBriefing $safetyBriefing)
    {
        $safetyBriefing->load(['images', 'user']);

        return view('pages.safety_briefing.show', compact('safetyBriefing'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SafetyBriefing $safetyBreafing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $briefing = SafetyBriefing::with('images')->findOrFail($id);

        // ===========================
        // VALIDASI
        // ===========================
        $request->validate([
            'tempat'            => 'required|string|max:255',
            'pekerjaan'         => 'required|string|max:255',
            'jumlah_peserta'    => 'required|integer|min:0',
            'catatan'           => 'nullable|string',

            'deleted_photos'    => 'array',
            'deleted_photos.*'  => 'integer',

            'existing_photos'   => 'array',
            'existing_photos.*' => 'integer',

            'new_photos.*'      => 'image|max:2048',
        ]);

        // ===========================
        // UPDATE DATA UTAMA
        // ===========================
        $briefing->update([
            'tempat'          => $request->tempat,
            'pekerjaan'       => $request->pekerjaan,
            'jumlah_peserta'  => $request->jumlah_peserta,
            'catatan'         => $request->catatan,
        ]);

        // ===========================
        // HAPUS FOTO LAMA
        // ===========================
        if ($request->deleted_photos) {
            foreach ($request->deleted_photos as $photoId) {
                $photo = ImageSafetyBriefing::find($photoId);
                if ($photo) {
                    Storage::disk('public')->delete($photo->image_url);
                    $photo->delete();
                }
            }
        }

        // ===========================
        // SIMPAN FOTO BARU
        // ===========================
        if ($request->hasFile('new_photos')) {
            foreach ($request->file('new_photos') as $file) {
                $path = $file->store('safety_briefing', 'public');

                $briefing->images()->create([
                    'image_url' => $path
                ]);
            }
        }

        return back()
            ->with('success', 'Data briefing berhasil diperbarui!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SafetyBriefing $safetyBriefing)
    {
        $safetyBriefing->delete();

        return redirect()->route('safety-briefing.index')->with('success', 'Berhasil menghapus safety briefing');
    }
}
