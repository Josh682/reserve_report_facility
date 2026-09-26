<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request): View
    {
        $currentTab = $request->query('tab', 'pending');
        $search = $request->query('search');
        $roleFilter = $request->query('role');
        $statusFilter = $request->query('status');

        $pendingCount = User::where('status_akun', 'pending')->count();
        $allCount = User::count();
        $petugasCount = User::where('role', 'petugas')->count();
        $penggunaCount = User::where('role', 'pengguna')->count();

        // Query untuk pending users (Tab 1)
        $pendingUsers = User::where('status_akun', 'pending')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10, ['*'], 'pending_page');

        // Query untuk semua users (Tab 2)
        $allUsers = User::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($roleFilter, function ($query, $role) {
                $query->where('role', $role);
            })
            ->when($statusFilter, function ($query, $status) {
                $query->where('status_akun', $status);
            })
            ->latest()
            ->paginate(10, ['*'], 'all_page');

        return view('admin.users.index', compact(
            'pendingUsers',
            'allUsers',
            'currentTab',
            'pendingCount',
            'allCount',
            'petugasCount',
            'penggunaCount',
            'search',
            'roleFilter',
            'statusFilter'
        ));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $role = $validated['role'];
        $tipePengguna = ($role === 'pengguna') ? ($validated['tipe_pengguna'] ?? 'mahasiswa') : null;

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $role,
            'tipe_pengguna' => $tipePengguna,
            'status_akun' => 'verified',
        ]);

        $message = ($role === 'petugas')
            ? 'Akun petugas berhasil dibuat dan langsung berstatus aktif.'
            : 'Akun pengguna berhasil dibuat dan langsung berstatus aktif.';

        return redirect()->route('admin.users.index', ['tab' => 'all'])
            ->with('status', $message);
    }

    /**
     * Approve a pending user account.
     */
    public function approve(User $user): RedirectResponse
    {
        $user->update(['status_akun' => 'verified']);

        return redirect()->back()
            ->with('status', "Akun {$user->name} berhasil diverifikasi dan disetujui.");
    }

    /**
     * Reject a pending user account.
     */
    public function reject(User $user): RedirectResponse
    {
        $user->update(['status_akun' => 'rejected']);

        return redirect()->back()
            ->with('status', "Akun {$user->name} telah ditolak.");
    }
}
