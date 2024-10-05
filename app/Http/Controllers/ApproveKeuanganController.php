<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Model yang sesuai
use App\Models\ApproveKeuangan;
use Illuminate\Support\Facades\Auth;

class ApproveKeuanganController extends Controller
{
    public function index()
    {
        $approveKeuangan = ApproveKeuangan::all();
        return view('approveKeuangan.index', compact('approveKeuangan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomorSurat' => 'required|string|max:255',
            'surat' => 'required|file|mimes:pdf|max:2048',
            'nomorBukti' => 'required|string|max:255',
            'buktiTransaksi' => 'required|file|mimes:pdf|max:2048',
        ]);

        $suratPath = $request->file('surat')->store('uploads/surat', 'public');
        $buktiPath = $request->file('buktiTransaksi')->store('uploads/bukti', 'public');

        $approveKeuangan = ApproveKeuangan::create([
            'nomorSurat' => $request->nomorSurat,
            'surat' => $suratPath,
            'nomorBukti' => $request->nomorBukti,
            'buktiTransaksi' => $buktiPath,
            'user_id' => Auth::id(), // Simpan ID pengguna yang mengajukan
        ]);
        $this->setLogActivity('Menambah Approve', $approveKeuangan);
        return redirect()->route('approve-keuangan.index')->with('success', 'Approve Keuangan berhasil ditambahkan!');
    }
    public function edit($id)
    {
        $approveKeuangan = ApproveKeuangan::findOrFail($id);
        return response()->json($approveKeuangan);
    }


    public function update(Request $request, $id)
    {
        $approveKeuangan = ApproveKeuangan::findOrFail($id);
    
        $request->validate([
            'nomorSurat' => 'required|string|max:255',
            'surat' => 'nullable|file|mimes:pdf|max:2048',
            'nomorBukti' => 'required|string|max:255',
            'buktiTransaksi' => 'nullable|file|mimes:pdf|max:2048',
        ]);
    
        $approveKeuangan->nomorSurat = $request->nomorSurat;
        $approveKeuangan->nomorBukti = $request->nomorBukti;
    
        if ($request->hasFile('surat')) {
            $approveKeuangan->surat = $request->file('surat')->store('uploads/surat', 'public');
        }
    
        if ($request->hasFile('buktiTransaksi')) {
            $approveKeuangan->buktiTransaksi = $request->file('buktiTransaksi')->store('uploads/bukti', 'public');
        }
    
        $approveKeuangan->save();
    
        return redirect()->route('approve-keuangan.index')->with('success', 'Approve Keuangan berhasil diperbarui!');
    }
    

    public function destroy($id)
    {
        $approveKeuangan = ApproveKeuangan::findOrFail($id);
        $approveKeuangan->delete();

        $this->setLogActivity('Menghapus Approve', $approveKeuangan);
        return redirect()->route('approveKeuangan.index')->with('success', 'Approve Keuangan berhasil dihapus.');
    }

}
