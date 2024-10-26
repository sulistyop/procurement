<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApproveKeuangan;
use Illuminate\Support\Facades\Auth;

class ApproveKeuanganController extends Controller
{
    public function index()
    {
        // Retrieve all approve keuangan entries
        $approveKeuangan = ApproveKeuangan::all();
		
        return view('approveKeuangan.index', compact('approveKeuangan'));
    }

    public function create()
    {
        // Show form to create new approve keuangan
        return view('approveKeuangan.create');
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'nomorSurat' => 'required|string|max:255',
            'surat' => 'required|file|mimes:pdf|max:2048',
            'nomorBukti' => 'required|string|max:255',
            'buktiTransaksi' => 'required|file|mimes:pdf|max:2048',
        ]);

        // Store files and create approve keuangan entry
        $suratPath = $request->file('surat')->store('uploads/surat', 'public');
        $buktiPath = $request->file('buktiTransaksi')->store('uploads/bukti', 'public');

        $approveKeuangan = ApproveKeuangan::create([
            'nomorSurat' => $request->nomorSurat,
            'surat' => $suratPath,
            'nomorBukti' => $request->nomorBukti,
            'buktiTransaksi' => $buktiPath,
            'user_id' => Auth::id(), // Save the ID of the user who submitted
        ]);

        // Log activity
        $this->setLogActivity('Menambah Approve Keuangan', $approveKeuangan);
        
        return redirect()->route('approve-keuangan.index')->with('success', 'Approve Keuangan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        // Retrieve approve keuangan entry for editing
        $approveKeuangan = ApproveKeuangan::findOrFail($id);
        return response()->json($approveKeuangan);
    }

    public function update(Request $request, $id)
    {
        // Find the entry to update
        $approveKeuangan = ApproveKeuangan::findOrFail($id);
        
        // Validate input
        $request->validate([
            'nomorSurat' => 'required|string|max:255',
            'surat' => 'nullable|file|mimes:pdf|max:2048',
            'nomorBukti' => 'required|string|max:255',
            'buktiTransaksi' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // Update fields
        $approveKeuangan->nomorSurat = $request->nomorSurat;
        $approveKeuangan->nomorBukti = $request->nomorBukti;

        if ($request->hasFile('surat')) {
            $approveKeuangan->surat = $request->file('surat')->store('uploads/surat', 'public');
        }

        if ($request->hasFile('buktiTransaksi')) {
            $approveKeuangan->buktiTransaksi = $request->file('buktiTransaksi')->store('uploads/bukti', 'public');
        }

        // Save the updated entry
        $approveKeuangan->save();
        
        // Log activity
        $this->setLogActivity('Mengubah Approve Keuangan', $approveKeuangan);
        
        return redirect()->route('approve-keuangan.index')->with('success', 'Approve Keuangan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Find the entry to delete
        $approveKeuangan = ApproveKeuangan::findOrFail($id);
        
        // Log activity before deletion
        $this->setLogActivity('Menghapus Approve Keuangan', $approveKeuangan);
        
        // Delete the entry
        $approveKeuangan->delete();
        
        return redirect()->route('approve-keuangan.index')->with('success', 'Approve Keuangan berhasil dihapus.');
    }
}
