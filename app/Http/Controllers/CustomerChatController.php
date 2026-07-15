<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerChatController extends Controller
{
    /**
     * Ambil atau buat session chat untuk customer yang sedang login.
     * Customer hanya boleh punya 1 session aktif (waiting/active).
     */
    public function getOrCreateSession(Request $request)
    {
        $user = Auth::user();

        // Cari session yang masih aktif/menunggu (belum completed dan belum expired)
        $session = ChatSession::where('user_id', $user->id)
            ->whereIn('status', ['waiting', 'active'])
            ->notExpired()
            ->latest()
            ->first();

        if (!$session) {
            // Buat session baru
            $now = Carbon::now();
            $session = ChatSession::create([
                'user_id'    => $user->id,
                'status'     => 'waiting',
                'started_at' => $now,
                'expires_at' => $now->copy()->addDays(60),
            ]);
        }

        return response()->json([
            'session_id' => $session->id,
            'status'     => $session->status,
            'admin_name' => $session->admin?->name,
        ]);
    }

    /**
     * Kirim pesan dari customer.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'session_id' => ['required', 'integer', 'exists:chat_sessions,id'],
            'message'    => ['required', 'string', 'max:2000'],
        ]);

        $user    = Auth::user();
        $session = ChatSession::where('id', $request->session_id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['waiting', 'active'])
            ->notExpired()
            ->firstOrFail();

        $msg = ChatMessage::create([
            'session_id'  => $session->id,
            'sender_id'   => $user->id,
            'sender_type' => 'customer',
            'message'     => trim($request->message),
            'is_read'     => false,
        ]);

        return response()->json([
            'id'          => $msg->id,
            'message'     => $msg->message,
            'sender_type' => 'customer',
            'time'        => $msg->created_at->format('H:i'),
        ]);
    }

    /**
     * Polling: ambil pesan terbaru sejak last_id.
     */
    public function getMessages(Request $request)
    {
        $request->validate([
            'session_id' => ['required', 'integer', 'exists:chat_sessions,id'],
            'last_id'    => ['nullable', 'integer'],
        ]);

        $user    = Auth::user();
        $session = ChatSession::where('id', $request->session_id)
            ->where('user_id', $user->id)
            ->notExpired()
            ->firstOrFail();

        $query = ChatMessage::where('session_id', $session->id);
        if ($request->last_id) {
            $query->where('id', '>', $request->last_id);
        }
        $messages = $query->orderBy('created_at')->get();

        // Tandai pesan dari admin sudah dibaca
        ChatMessage::where('session_id', $session->id)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'status'     => $session->status,
            'admin_name' => $session->admin?->name,
            'messages'   => $messages->map(fn($m) => [
                'id'          => $m->id,
                'message'     => $m->message,
                'sender_type' => $m->sender_type,
                'time'        => $m->created_at->format('H:i'),
            ]),
        ]);
    }

    /**
     * Ambil semua pesan history dari awal session.
     */
    public function getHistory(Request $request)
    {
        $request->validate([
            'session_id' => ['required', 'integer', 'exists:chat_sessions,id'],
        ]);

        $user    = Auth::user();
        $session = ChatSession::where('id', $request->session_id)
            ->where('user_id', $user->id)
            ->notExpired()
            ->firstOrFail();

        $messages = ChatMessage::where('session_id', $session->id)
            ->orderBy('created_at')
            ->get();

        // Tandai pesan admin sudah dibaca
        ChatMessage::where('session_id', $session->id)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'status'     => $session->status,
            'admin_name' => $session->admin?->name,
            'messages'   => $messages->map(fn($m) => [
                'id'          => $m->id,
                'message'     => $m->message,
                'sender_type' => $m->sender_type,
                'time'        => $m->created_at->format('H:i'),
                'date'        => $m->created_at->format('d M Y'),
            ]),
        ]);
    }
}
