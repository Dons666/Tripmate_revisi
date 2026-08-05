<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Destinasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BookmarkController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $bookmarks = $user->bookmarks()->with('destinasi')->latest()->get();
        return view('bookmarks.index', compact('bookmarks'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'destinasi_id' => 'required|exists:destinasi,id',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $destinasi_id = $request->input('destinasi_id');

        // Cek apakah sudah di bookmark
        $bookmark = $user->bookmarks()->where('destinasi_id', $destinasi_id)->first();

        if ($bookmark) {
            // Jika ada, hapus (Unbookmark)
            $bookmark->delete();
            return back()->with('success', 'Bookmark dihapus.');
        } else {
            // Jika tidak ada, buat (Bookmark)
            try {
                $user->bookmarks()->create(['destinasi_id' => $destinasi_id]);
                return back()->with('success', 'Destinasi dibookmark!');
            } catch (\Exception $e) {
                // Tangani jika sudah ada di database (Unique constraint violation)
                return back()->with('error', 'Gagal menyimpan bookmark.');
            }
        }
    }
}
