<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\MemberSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription()->with('membership')->first();
        $attendanceCount = $user->attendanceLogs()->whereMonth('scanned_at', now()->month)->count();
        $recentAttendance = $user->attendanceLogs()->with('subscription.membership')->latest('scanned_at')->take(5)->get();

        return view('customer.dashboard', compact('user', 'subscription', 'attendanceCount', 'recentAttendance'));
    }

    public function profile()
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription()->with('membership')->first();
        return view('customer.profile', compact('user', 'subscription'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'gender' => ['nullable', 'in:male,female'],
            'birth_date' => ['nullable', 'date'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $validated['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $user->update($validated);
        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function membership()
    {
        $user = Auth::user();
        $memberships = Membership::where('is_active', true)->get();
        $activeSubscription = $user->activeSubscription()->with('membership')->first();
        $pendingSubscription = $user->subscriptions()->where('status', 'pending')->with('membership')->first();

        return view('customer.membership', compact('memberships', 'activeSubscription', 'pendingSubscription', 'user'));
    }

    public function buyMembership(Request $request)
    {
        $user = Auth::user();

        // Cek tidak ada pending atau aktif yang masih berlaku
        $existing = $user->subscriptions()
            ->where(function ($q) {
                $q->where('status', 'pending')
                  ->orWhere(function ($q2) {
                      $q2->where('status', 'active')
                         ->where('end_date', '>=', now()->toDateString());
                  });
            })
            ->first();

        if ($existing) {
            $msg = $existing->status === 'pending'
                ? 'Anda masih memiliki pembayaran yang sedang menunggu konfirmasi admin.'
                : 'Anda masih memiliki membership yang aktif.';
            return back()->with('error', $msg);
        }

        $validated = $request->validate([
            'membership_id' => ['required', 'exists:memberships,id'],
            'payment_proof' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'payment_proof.required' => 'Bukti pembayaran wajib diunggah.',
            'payment_proof.image'    => 'File harus berupa gambar.',
            'payment_proof.mimes'    => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'payment_proof.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        $membership = Membership::findOrFail($validated['membership_id']);

        // Simpan file bukti pembayaran
        if (!$request->hasFile('payment_proof') || !$request->file('payment_proof')->isValid()) {
            return back()->with('error', 'Gagal mengunggah file. Coba lagi.');
        }

        $paymentPath = $request->file('payment_proof')->store('payment_proofs', 'public');

        if (!$paymentPath) {
            Log::error('Payment proof upload failed for user ' . $user->id);
            return back()->with('error', 'Gagal menyimpan bukti pembayaran. Silakan hubungi admin.');
        }

        MemberSubscription::create([
            'user_id'       => $user->id,
            'membership_id' => $membership->id,
            'status'        => 'pending',
            'payment_proof' => $paymentPath,
            'amount_paid'   => $membership->price,
        ]);

        return redirect()->route('customer.payment.status')
            ->with('success', '✅ Bukti pembayaran berhasil diunggah! Menunggu konfirmasi dari admin.');
    }

    /**
     * Halaman status pembayaran untuk customer.
     */
    public function paymentStatus()
    {
        $user = Auth::user();
        $subscriptions = $user->subscriptions()
            ->with('membership')
            ->latest()
            ->paginate(10);

        return view('customer.payment-status', compact('user', 'subscriptions'));
    }
}
