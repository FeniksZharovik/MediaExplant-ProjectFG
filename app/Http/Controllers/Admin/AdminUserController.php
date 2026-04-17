<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\API\Bookmark;
use App\Models\API\Reaksi;
use App\Models\API\Komentar;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
    public function user(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $searchTerm = $request->input('search', '');

        $query = User::query();

        // 🔍 Search
        if ($searchTerm) {
            $query->where('nama_pengguna', 'like', "%$searchTerm%")
                ->orWhere('email', 'like', "%$searchTerm%");
        }

        // 📦 Filter by role
        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        // 🗓️ Filter by date range
        if ($tanggalDari = $request->input('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $tanggalDari);
        }

        if ($tanggalSampai = $request->input('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $tanggalSampai);
        }

        // 🔁 Sort by date
        if ($order = $request->input('order')) {
            if ($order === 'terbaru') {
                $query->orderByDesc('created_at');
            } elseif ($order === 'terlama') {
                $query->orderBy('created_at');
            }
        }

        // 📄 Paginate and preserve query params
        $users = $query->paginate($perPage)->appends([
            'search' => $searchTerm,
            'perPage' => $perPage,
            'role' => $request->input('role'),
            'order' => $request->input('order'),
            'tanggal_dari' => $request->input('tanggal_dari'),
            'tanggal_sampai' => $request->input('tanggal_sampai'),
        ]);

        return view('dashboard-admin.menu.user.user', compact('users', 'perPage', 'searchTerm'));
    }

    public function detail(Request $request, $id)
    {
        // Fetch user with relationships to avoid N+1 queries
        $user = User::with(['bookmarks', 'reaksi', 'komentar'])->findOrFail($id);

        return view('dashboard-admin.menu.user.detail', compact('user'));
    }

    public function deleteKomen(Request $request, $id, $komentarId)
    {
        // Start recursive deletion
        $this->deleteCommentAndChildren($komentarId);

        return redirect()
            ->route('admin.user.detail', $id)
            ->with('success', 'Komentar berhasil dihapus');
    }

    /**
     * Recursively delete a comment and all its children.
     */
    private function deleteCommentAndChildren(string $commentId)
    {
        // 1. Find all direct children of this comment
        $children = DB::table('komentar')
            ->where('parent_id', $commentId)
            ->pluck('id')
            ->toArray();

        // 2. Recursively delete all children
        foreach ($children as $childId) {
            $this->deleteCommentAndChildren($childId);
        }

        // 3. Delete the current comment
        DB::table('komentar')->where('id', $commentId)->delete();
    }

    public function deleteUser(Request $request, $uid)
    {
        $user = User::findOrFail($uid);
        $user->delete();
    
        return redirect()
            ->route('admin.user')
            ->with('success', 'Pengguna berhasil dihapus');
    }

    public function updateRole(Request $request, $uid)
    {
        $request->validate([
            'role' => 'required|in:Admin,Penulis,Pembaca',
        ]);

        $user = User::findOrFail($uid);
        $user->role = $request->input('role');
        $user->save();

        if ($request->ajax()) {
            return response()->json(['success' => 'Role berhasil diubah']);
        }

        return redirect()
            ->route('admin.user')
            ->with('success', 'Peran pengguna berhasil diubah');
    }
}