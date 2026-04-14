<?php

namespace App\Http\Controllers;

use App\Models\StockApdTransaction;
use App\Models\Tool;
use App\Models\ToolStockHistory;
use Illuminate\Http\Request;

class ToolStockHistoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tool_id' => 'required|exists:tools,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string'
        ]);

        $apd = Tool::findOrFail($request->tool_id);

        // 🔴 VALIDASI STOK TIDAK BOLEH MINUS
        if ($request->type === 'out' && $apd->stock < $request->quantity) {
            return back()->with('error', 'Stok tidak mencukupi!');
        }

        // 💾 SIMPAN TRANSAKSI
        StockApdTransaction::create([
            'tool_id' => $apd->id,
            'type' => $request->type,
            'quantity' => $request->quantity,
            'note' => $request->note
        ]);

        // 🔄 UPDATE STOK
        if ($request->type === 'in') {
            $apd->increment('stock', $request->quantity);
        } else {
            $apd->decrement('stock', $request->quantity);
        }

        return back()->with('success', 'Transaksi berhasil!');
    }
}
