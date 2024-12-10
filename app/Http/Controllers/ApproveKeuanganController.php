<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApproveKeuangan;
use App\Models\ParentPengajuan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ApproveKeuanganController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->toDateString();

        $existingParentPengajuanIds = DB::table('approve_keuangan_parent_pengajuan')
                                        ->pluck('parent_pengajuan_id')
                                        ->toArray();
    
        $query = ApproveKeuangan::with(['parents', 'parents.prodi']);
    
        $tanggalDari = $request->input('tanggal_dari', $today);
        $tanggalSampai = $request->input('tanggal_sampai', $today);
    
        $query->whereBetween('created_at', [
            $tanggalDari . ' 00:00:00',
            $tanggalSampai . ' 23:59:59'
        ]);
    
        $approveKeuangan = $query->get();

        $parents = ParentPengajuan::all();
    
        return view('admin.approveKeuangan.index', compact('approveKeuangan', 'parents', 'existingParentPengajuanIds', 'tanggalDari', 'tanggalSampai'));
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
            return redirect()->route('approve-keuangan.index')->with('error', 'Terjadi kesalahan saat menambahkan laporan keuangan. Silahkan Pilih Rumah Pengajuan.');
        }
    }

    public function edit(Request $request)
    {
        $nomorSurat = $request->input('nomorSurat');
        $nomorBukti = $request->input('nomorBukti');
        $parents = ParentPengajuan::all();
        $approveKeuangan = ApproveKeuangan::where('nomorSurat', $nomorSurat)
                                          ->orWhere('nomorBukti', $nomorBukti)
                                          ->first();
        if ($approveKeuangan) {
            $selectedParents = $approveKeuangan->parents->pluck('id')->toArray();
            return response()->json([
                'approveKeuangan' => $approveKeuangan,
                'selectedParents' => $selectedParents,
                'parents' => $parents,
            ]);
        } else {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
    }    

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nomorSurat' => 'required|string|max:255',
                'nomorBukti' => 'required|string|max:255',
                'surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx,xlsx,pptx,txt,zip|max:2048',
                'buktiTransaksi' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx,xlsx,pptx,txt,zip|max:2048',
                'parent_pengajuan_id' => 'required|array',
                'parent_pengajuan_id.*' => 'exists:parent_pengajuan,id',
            ]);
        
            $approveKeuangan = ApproveKeuangan::findOrFail($id);
            $approveKeuangan->nomorSurat = $request->nomorSurat;
            $approveKeuangan->nomorBukti = $request->nomorBukti;

            if ($request->hasFile('surat')) {
                $approveKeuangan->surat = $request->file('surat')->store('uploads/surat', 'public');
            }

            if ($request->hasFile('buktiTransaksi')) {
                $approveKeuangan->buktiTransaksi = $request->file('buktiTransaksi')->store('uploads/bukti', 'public');
            }

            $approveKeuangan->save();
            $approveKeuangan->parents()->sync($request->parent_pengajuan_id);
            $this->setLogActivity('Mengubah Approve Keuangan', $approveKeuangan);
            return redirect()->route('approve-keuangan.index')->with('success', 'Approve Keuangan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('approve-keuangan.index', ['id' => $id])->with('error', 'Terjadi kesalahan saat memperbarui Approve Keuangan.');
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
