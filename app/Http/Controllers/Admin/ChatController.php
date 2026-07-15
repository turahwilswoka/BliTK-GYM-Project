<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Daftar semua session chat (waiting & active).
     */
    public function index()
    {
        $waiting = ChatSession::where('status', 'waiting')
            ->notExpired()
            ->with(['user', 'messages' => fn($q) => $q->latest()->limit(1)])
            ->withCount(['messages as unread_count' => fn($q) => $q->where('sender_type', 'customer')->where('is_read', false)])
            ->latest()
            ->get();

        $active = ChatSession::where('status', 'active')
            ->notExpired()
            ->with(['user', 'admin', 'messages' => fn($q) => $q->latest()->limit(1)])
            ->withCount(['messages as unread_count' => fn($q) => $q->where('sender_type', 'customer')->where('is_read', false)])
            ->latest('claimed_at')
            ->get();

        $completed = ChatSession::where('status', 'completed')
            ->notExpired()
            ->with(['user', 'admin'])
            ->latest('completed_at')
            ->limit(20)
            ->get();

        return view('admin.chat.index', compact('waiting', 'active', 'completed'));
    }

    /**
     * Admin klaim session yang masih waiting.
     * Menggunakan DB transaction + atomic update untuk mencegah double-claim.
     */
    public function claim(Request $request, ChatSession $session)
    {
        $claimed = DB::transaction(function () use ($session) {
            // Lock row untuk mencegah race condition
            $locked = ChatSession::where('id', $session->id)
                ->where('status', 'waiting')
                ->lockForUpdate()
                ->first();

            if (!$locked) {
                return false; // sudah diklaim atau bukan waiting
            }

            $locked->update([
                'admin_id'   => Auth::id(),
                'status'     => 'active',
                'claimed_at' => now(),
            ]);

            return true;
        });

        if (!$claimed) {
            return back()->with('error', 'Percakapan ini sudah diambil oleh admin lain.');
        }

        return redirect()->route('admin.chat.show', $session)
            ->with('success', 'Percakapan berhasil diambil.');
    }

    /**
     * Tampilkan halaman chat untuk admin.
     */
    public function show(ChatSession $session)
    {
        // Validasi: hanya admin yang meng-klaim yang bisa chat di sesi active
        if ($session->isActive() && $session->admin_id !== Auth::id()) {
            return redirect()->route('admin.chat.index')
                ->with('error', 'Anda tidak memiliki akses ke percakapan ini.');
        }

        // Boleh lihat session waiting (belum diklaim)
        $session->load(['user', 'admin', 'messages.sender']);

        // Tandai pesan customer sudah dibaca
        ChatMessage::where('session_id', $session->id)
            ->where('sender_type', 'customer')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('admin.chat.show', compact('session'));
    }

    /**
     * Admin kirim pesan ke customer.
     */
    public function sendMessage(Request $request, ChatSession $session)
    {
        if ($session->admin_id !== Auth::id() || !$session->isActive()) {
            return response()->json(['error' => 'Tidak diizinkan.'], 403);
        }

        $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $msg = ChatMessage::create([
            'session_id'  => $session->id,
            'sender_id'   => Auth::id(),
            'sender_type' => 'admin',
            'message'     => trim($request->message),
            'is_read'     => false,
        ]);

        return response()->json([
            'id'          => $msg->id,
            'message'     => $msg->message,
            'sender_type' => 'admin',
            'time'        => $msg->created_at->format('H:i'),
        ]);
    }

    /**
     * Admin polling pesan baru.
     */
    public function getMessages(Request $request, ChatSession $session)
    {
        if ($session->admin_id !== Auth::id() && !$session->isWaiting()) {
            return response()->json(['error' => 'Tidak diizinkan.'], 403);
        }

        $query = ChatMessage::where('session_id', $session->id);
        if ($request->last_id) {
            $query->where('id', '>', (int)$request->last_id);
        }
        $messages = $query->orderBy('created_at')->get();

        // Tandai customer messages sudah dibaca
        ChatMessage::where('session_id', $session->id)
            ->where('sender_type', 'customer')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'status'   => $session->status,
            'messages' => $messages->map(fn($m) => [
                'id'          => $m->id,
                'message'     => $m->message,
                'sender_type' => $m->sender_type,
                'time'        => $m->created_at->format('H:i'),
            ]),
        ]);
    }

    /**
     * Admin selesaikan percakapan.
     */
    public function complete(ChatSession $session)
    {
        if ($session->admin_id !== Auth::id() || !$session->isActive()) {
            return back()->with('error', 'Tidak dapat menyelesaikan percakapan ini.');
        }

        $session->update([
            'status'       => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()->route('admin.chat.index')
            ->with('success', 'Percakapan telah diselesaikan.');
    }

    /**
     * Badge count untuk navbar: jumlah waiting + unread.
     */
    public function pendingCount()
    {
        $count = ChatSession::where('status', 'waiting')
            ->notExpired()
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Polling semua session untuk panel list admin (auto-refresh).
     */
    public function listPoll()
    {
        $waiting = ChatSession::where('status', 'waiting')
            ->notExpired()
            ->with('user')
            ->withCount(['messages as unread_count' => fn($q) => $q->where('sender_type', 'customer')->where('is_read', false)])
            ->latest()
            ->get()
            ->map(fn($s) => [
                'id'           => $s->id,
                'user_name'    => $s->user->name,
                'unread_count' => $s->unread_count,
                'started_at'   => $s->started_at->diffForHumans(),
            ]);

        $myActive = ChatSession::where('status', 'active')
            ->where('admin_id', Auth::id())
            ->notExpired()
            ->with('user')
            ->withCount(['messages as unread_count' => fn($q) => $q->where('sender_type', 'customer')->where('is_read', false)])
            ->latest('claimed_at')
            ->get()
            ->map(fn($s) => [
                'id'           => $s->id,
                'user_name'    => $s->user->name,
                'unread_count' => $s->unread_count,
                'claimed_at'   => $s->claimed_at->diffForHumans(),
            ]);

        return response()->json([
            'waiting'   => $waiting,
            'my_active' => $myActive,
        ]);
    }
}
