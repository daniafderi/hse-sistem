<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanItem;
use App\Models\Tool;
use App\Models\ReturnRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loans = Loan::with('user', 'items.tool')->latest()->paginate(12);
        return view('pages.tool.loan.index', compact('loans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tools = Tool::where('stock', '>', 0)->where('validation', 'valid')->orderBy('name')->get();
        return view('pages.tool.loan.create', compact('tools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.tool_id' => 'required|exists:tools,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'peminjam' => 'required|string'
        ]);
        //dd($request);
        $user = $request->user();
        DB::transaction(function () use ($request, $user) {
            $loan = Loan::create([
                'peminjam' => $request->peminjam,
                'status' => 'borrowed',
                'borrowed_at' => Carbon::now(),
                'notes' => $request->notes,
            ]);
            foreach ($request->items as $it) {
                $tool = Tool::lockForUpdate()->find($it['tool_id']);
                $qty = (int)$it['quantity'];
                if ($tool->stock < $qty) {
                    throw new \Exception("Stok untuk {$tool->name} tidak
mencukupi.");
                }
                // kurangi stok
                $tool->decrement('stock', $qty);
                $loan->items()->create([
                    'tool_id' => $tool->id,
                    'quantity' => $qty,
                ]);
            }
        });
        return redirect()->route('loans.index')->with('success', 'Peminjaman
berhasil dicatat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan)
    {
        $loan->load('items.tool', 'user');
        //dd($loan);
        $returnRecords = DB::table('return_records')
        ->join('loan_items', 'return_records.loan_item_id', '=', 'loan_items.id')
        ->join('tools', 'loan_items.tool_id', '=', 'tools.id')
        ->select(
            'return_records.returned_at',
            'return_records.quantity',
            'return_records.condition',
            'tools.name'
        )
        ->where('loan_items.loan_id', $loan->id) // contoh: $loanId = 3
        ->orderBy('returned_at','desc')
        ->get();

            //dd($returnRecords);

        return view('pages.tool.loan.show', compact(['loan','returnRecords']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Loan $loan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Loan $loan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loan $loan)
    {
        //
    }

    public function return(Loan $loan, Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:loan_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.condition_on_return' => 'nullable|string',
        ]);

        DB::transaction(function () use ($loan, $request) {
            foreach ($request->items as $it) {
                $item = LoanItem::lockForUpdate()->find($it['item_id']);
                $tool = Tool::lockForUpdate()->find($item->tool_id);

                $qtyReturn = (int) $it['quantity'];
                $remaining = $item->quantity - $item->returned_quantity;

                if ($qtyReturn > $remaining) {
                    throw new \Exception("Jumlah pengembalian melebihi sisa pinjaman untuk {$tool->name}.");
                }

                // 1️⃣ Update total returned quantity
                $item->increment('returned_quantity', $qtyReturn);

                // 2️⃣ Tambahkan stok kembali ke alat
                $tool->increment('stock', $qtyReturn);

                // 3️⃣ Simpan catatan pengembalian baru
                \App\Models\ReturnRecord::create([
                    'loan_item_id' => $item->id,
                    'quantity' => $qtyReturn,
                    'condition' => $it['condition_on_return'] ?? null,
                    'returned_at' => now(),
                ]);
            }

            // 4️⃣ Update status pinjaman
            $allReturned = $loan->items->every(fn($i) => $i->returned_quantity >= $i->quantity);
            $loan->update([
                'status' => $allReturned ? 'returned' : 'partial_return',
                'returned_at' => $allReturned ? now() : null,
            ]);
        });

        return redirect()->route('loans.show', $loan)
            ->with('success', 'Pengembalian alat berhasil dicatat.');
    }
}
