<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApproveKeuangan;
use App\Models\ParentPengajuan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ApproveKeuanganController extends Controller
{
    public function index()
    {
        // Ambil ID parent_pengajuan yang sudah ada di tabel approve_keuangan_parent_pengajuan
        $existingParentPengajuanIds = DB::table('approve_keuangan_parent_pengajuan')
                                        ->pluck('parent_pengajuan_id') // Ambil hanya ID-nya
                                        ->toArray(); // Convert ke array
    
        // Ambil semua data approve_keuangan
        $approveKeuangan = ApproveKeuangan::all();
    
        // Ambil data parent pengajuan untuk form
        $parents = ParentPengajuan::all();
    
        // Kirim data ke view
        return view('admin.approveKeuangan.index', compact('approveKeuangan', 'parents', 'existingParentPengajuanIds'));
    }
    
    public function create()
    {
        $parents = ParentPengajuan::all(); 
        return view('admin.approveKeuangan.index', compact('parents'));
    }
    
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nomorSurat' => 'required|string',
                'surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx,xlsx,pptx,txt,zip|max:2048',
                'nomorBukti' => 'required|string',
                'buktiTransaksi' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx,xlsx,pptx,txt,zip|max:2048',
                'parent_pengajuan_id' => 'required|array',
                'parent_pengajuan_id.*' => 'exists:parent_pengajuan,id',
            ]);
    
            $approveKeuangan = new ApproveKeuangan();
            $approveKeuangan->nomorSurat = $validated['nomorSurat'];
            $approveKeuangan->nomorBukti = $validated['nomorBukti'];
    
            if ($request->hasFile('surat')) {
                $suratPath = $request->file('surat')->store('public/surat');
                $approveKeuangan->surat = str_replace('public/', '', $suratPath);
            }
    
            if ($request->hasFile('buktiTransaksi')) {
                $buktiPath = $request->file('buktiTransaksi')->store('public/bukti-transaksi');
                $approveKeuangan->buktiTransaksi = str_replace('public/', '', $buktiPath);
            }
    
            $approveKeuangan->save();
            $approveKeuangan->parents()->sync($validated['parent_pengajuan_id']);
    
            return redirect()->route('approve-keuangan.index')->with('success', 'Laporan Keuangan berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->route('approve-keuangan.index')
                             ->with('error', 'Terjadi kesalahan saat menambahkan laporan keuangan. Silahkan Pilih Rumah Pengajuan.');
        }
    }
    
    
    public function edit(Request $request)
    {
        // Ambil parameter lain dari request
        $nomorSurat = $request->input('nomorSurat');
        $nomorBukti = $request->input('nomorBukti');
        
        // Anda juga bisa mengambil relasi atau data lain jika diperlukan
        $parents = ParentPengajuan::all();
    
        // Ambil data berdasarkan parameter dari request, misalnya berdasarkan nomorSurat atau nomorBukti
        $approveKeuangan = ApproveKeuangan::where('nomorSurat', $nomorSurat)
                                          ->orWhere('nomorBukti', $nomorBukti)
                                          ->first();  // Sesuaikan dengan kondisi pencarian yang diinginkan
    
        if ($approveKeuangan) {
            // Mendapatkan ID dari relasi many-to-many
            $selectedParents = $approveKeuangan->parents->pluck('id')->toArray();
    
            // Mengembalikan data dalam format JSON untuk digunakan oleh AJAX
            return response()->json([
                'approveKeuangan' => $approveKeuangan,
                'selectedParents' => $selectedParents,
                'parents' => $parents,
            ]);
        } else {
            // Jika data tidak ditemukan
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
    }    
    
    public function update(Request $request, $id)
    {
        try {
            // Validasi input
            $request->validate([
                'nomorSurat' => 'required|string|max:255',
                'nomorBukti' => 'required|string|max:255',
                'surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx,xlsx,pptx,txt,zip|max:2048',
                'buktiTransaksi' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx,xlsx,pptx,txt,zip|max:2048',
                'parent_pengajuan_id' => 'required|array',  // Validasi agar yang dipilih adalah array
                'parent_pengajuan_id.*' => 'exists:parent_pengajuan,id',
            ]);
        
            // Temukan entri ApproveKeuangan berdasarkan ID
            $approveKeuangan = ApproveKeuangan::findOrFail($id);
        
            // Update field ApproveKeuangan
            $approveKeuangan->nomorSurat = $request->nomorSurat;
            $approveKeuangan->nomorBukti = $request->nomorBukti;
        
            // Proses file upload jika ada
            if ($request->hasFile('surat')) {
                try {
                    $approveKeuangan->surat = $request->file('surat')->store('uploads/surat', 'public');
                } catch (\Exception $e) {
                    return redirect()->route('approve-keuangan.edit', ['id' => $id])
                                     ->with('error', 'Gagal mengunggah file Surat. Pastikan file memiliki format yang sesuai dan ukuran tidak melebihi batas.');
                }
            }
        
            if ($request->hasFile('buktiTransaksi')) {
                try {
                    $approveKeuangan->buktiTransaksi = $request->file('buktiTransaksi')->store('uploads/bukti', 'public');
                } catch (\Exception $e) {
                    return redirect()->route('approve-keuangan.index', ['id' => $id])
                                     ->with('error', 'Gagal mengunggah file Bukti Transaksi. Pastikan file memiliki format yang sesuai dan ukuran tidak melebihi batas.');
                }
            }
        
            // Menyimpan perubahan data ApproveKeuangan
            $approveKeuangan->save();
        
            // Menyinkronkan parent_pengajuan_id yang dipilih
            $approveKeuangan->parents()->sync($request->parent_pengajuan_id); // Memastikan hubungan many-to-many
        
            // Log aktivitas jika perlu
            $this->setLogActivity('Mengubah Approve Keuangan', $approveKeuangan);
        
            // Redirect setelah berhasil
            return redirect()->route('approve-keuangan.index')->with('success', 'Approve Keuangan berhasil diperbarui!');
        } catch (\Exception $e) {
            // Tangani kesalahan jika update gagal
            return redirect()->route('approve-keuangan.index', ['id' => $id])
                             ->with('error', 'Terjadi kesalahan saat memperbarui Approve Keuangan.');
        }
    }    
    
    public function destroy($id)
    {

        $approveKeuangan = ApproveKeuangan::findOrFail($id);
    
        $approveKeuangan->parents()->detach(); 
        
        $this->setLogActivity('Menghapus Approve Keuangan', $approveKeuangan);
        
        $approveKeuangan->delete();
        
        return redirect()->route('approve-keuangan.index')->with('success', 'Approve Keuangan berhasil dihapus.');
    }
}
