<?php

namespace App\Http\Controllers;

use App\Models\StockApdTransaction;
use App\Models\Tool;
use App\Models\ToolStockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\User;

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
            'note' => $request->note,
            'user_id' => auth()->id(),
            'stock_before' => $apd->stock
        ]);

        // 🔄 UPDATE STOK
        if ($request->type === 'in') {
            $apd->increment('stock', $request->quantity);
        } else {
            $apd->decrement('stock', $request->quantity);
        }

        if ($apd->stock < ($apd->stock_minimum * 0.10)) {
            $notif = Notification::create([
                'type' => 'report_created',
                'title' => 'Stock APD Menipis',
                'message' => 'Stock APD ' . $apd->name . ' dibawah kebutuhan pertahun',
                'notifiable_id' => $apd->id,
                'notifiable_type' => Tool::class,
                'created_by' => auth()->id()
            ]);

            $users = User::whereIn('role', ['HSE Kantor', 'Supervisor'])->pluck('id');

            // kirim ke user tertentu
            $notif->users()->attach($users);
        }

        return back()->with('success', 'Transaksi berhasil!');
    }
}
