<?php

namespace App\Http\Controllers\Admin\Perusahaan;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    private function baseQuery()
    {
        return Notifikasi::where('id_pengguna', Auth::user()->id_pengguna);
    }

    public function index(Request $request)
    {
        $tab = $request->get('tab', 'all');

        $baseQuery = $this->baseQuery();

        $countAll     = (clone $baseQuery)->count();
        $countUnread  = (clone $baseQuery)->where('status_baca', false)->count();
        $countDeleted = (clone $baseQuery)->onlyTrashed()->count();

        $query = $this->baseQuery();

        if ($tab === 'unread') {
            $query->where('status_baca', false);
            $title = 'Notifikasi Belum Dibaca';
        } elseif ($tab === 'deleted') {
            $query = $query->onlyTrashed();
            $title = 'Notifikasi Terhapus';
        } else {
            $title = 'Semua Notifikasi';
        }

        $notifikasi = $query->latest()->get();

        return view('admin.perusahaan.notifikasi.index', compact(
            'notifikasi',
            'tab',
            'countAll',
            'countUnread',
            'countDeleted',
            'title'
        ));
    }

    public function show($id)
    {
        $notifikasi = $this->baseQuery()->findOrFail($id);

        if (!$notifikasi->status_baca) {
            $notifikasi->update(['status_baca' => true]);
        }

        return redirect()
            ->route('perusahaan.notifikasi.index')
            ->with('success', 'Notifikasi ditandai sebagai sudah dibaca.');
    }

    public function destroy($id)
    {
        $notifikasi = $this->baseQuery()->findOrFail($id);
        $notifikasi->delete();

        return redirect()
            ->route('perusahaan.notifikasi.index')
            ->with('success', 'Notifikasi berhasil dihapus.');
    }

    public function restore($id)
    {
        $notifikasi = $this->baseQuery()->onlyTrashed()->findOrFail($id);
        $notifikasi->restore();

        return back()->with('success', 'Notifikasi berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        $notifikasi = $this->baseQuery()->onlyTrashed()->findOrFail($id);
        $notifikasi->forceDelete();

        return back()->with('success', 'Notifikasi dihapus permanen.');
    }

    public function markSelected(Request $request)
    {
        $ids = $request->ids ?? [];

        Notifikasi::whereIn('id_notifikasi', $ids)
            ->where('id_pengguna', Auth::user()->id_pengguna)
            ->whereNull('deleted_at')
            ->update(['status_baca' => true]);

        return response()->json(['success' => true]);
    }

    public function markAll()
    {
        Notifikasi::where('id_pengguna', Auth::user()->id_pengguna)
            ->whereNull('deleted_at')
            ->update(['status_baca' => true]);

        return response()->json(['success' => true]);
    }

    public function deleteSelected(Request $request)
    {
        $ids = $request->ids ?? [];

        Notifikasi::whereIn('id_notifikasi', $ids)
            ->where('id_pengguna', Auth::user()->id_pengguna)
            ->whereNull('deleted_at')
            ->delete();

        return response()->json(['success' => true]);
    }

    public function deleteAll()
    {
        Notifikasi::where('id_pengguna', Auth::user()->id_pengguna)
            ->whereNull('deleted_at')
            ->delete();

        return response()->json(['success' => true]);
    }

    public function restoreSelected(Request $request)
    {
        $ids = $request->ids ?? [];

        Notifikasi::onlyTrashed()
            ->whereIn('id_notifikasi', $ids)
            ->where('id_pengguna', Auth::user()->id_pengguna)
            ->restore();

        return response()->json(['success' => true]);
    }

    public function forceDeleteSelected(Request $request)
    {
        $ids = $request->ids ?? [];

        Notifikasi::onlyTrashed()
            ->whereIn('id_notifikasi', $ids)
            ->where('id_pengguna', Auth::user()->id_pengguna)
            ->forceDelete();

        return response()->json(['success' => true]);
    }

    public function forceDeleteAll()
    {
        Notifikasi::onlyTrashed()
            ->where('id_pengguna', Auth::user()->id_pengguna)
            ->forceDelete();

        return response()->json(['success' => true]);
    }
}
