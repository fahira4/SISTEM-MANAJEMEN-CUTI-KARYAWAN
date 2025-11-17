<?php

namespace App\Http\Controllers; // Perbaikan 1: Namespace yang benar

use App\Models\LeaveApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveApplicationController extends Controller // Pastikan 'extends Controller'
{

    public function index()
    {
        // 1. Ambil ID user yang sedang login
        $userId = Auth::id();

        // 2. Ambil semua data cuti HANYA milik user tersebut
        // 'latest()' -> urutkan dari yang paling baru
        $leaveApplications = LeaveApplication::where('user_id', $userId)
                                             ->latest()
                                             ->get();

        // 3. Kirim data ke view
        return view('leave-applications.index', compact('leaveApplications'));
    }

    public function create()
    {
        // Hanya menampilkan view formulir
        return view('leave-applications.create');
    }

    /**
     * Menyimpan pengajuan cuti baru ke database.
     */
    public function store(Request $request) // Perbaikan 2: Nama fungsi 'store' yang benar
    {
        // 1. --------- VALIDASI DATA (SESUAI PDF) ---------
        $request->validate([
            'leave_type' => 'required|in:tahunan,sakit',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:10',
            'address_during_leave' => 'required|string|min:5',
            'emergency_contact' => 'required|string|min:9',
            'attachment_path' => 'required_if:leave_type,sakit|file|mimes:pdf,jpg,png|max:2048', // Wajib jika 'leave_type' adalah 'sakit'
        ]);

        $user = Auth::user();
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // 2. --------- HITUNG TOTAL HARI KERJA ---------
        // (Menghitung hari Senin - Jumat saja, sesuai PDF)
        $totalDays = $startDate->diffInWeekdays($endDate) + 1; // +1 untuk includekan hari mulai

        // 3. --------- VALIDASI LOGIKA CUTI TAHUNAN ---------
        if ($request->leave_type == 'tahunan') {
            // Validasi 1: Kuota harus cukup
            if ($user->annual_leave_quota < $totalDays) {
                return back()->withInput()->withErrors(['total_days' => 'Sisa kuota cuti tahunan Anda tidak mencukupi (Sisa: ' . $user->annual_leave_quota . ' hari).']);
            }

            // Validasi 2: Minimal H-3
            if ($startDate->isBefore(Carbon::today()->addDays(3))) {
                return back()->withInput()->withErrors(['start_date' => 'Pengajuan Cuti Tahunan harus minimal H-3 (3 hari) sebelum tanggal mulai cuti.']);
            }
        }
        
        // 4. --------- PROSES UPLOAD FILE (JIKA ADA) ---------
        $attachmentPath = null;
        if ($request->hasFile('attachment_path')) {
            $attachmentPath = $request->file('attachment_path')->store('attachments', 'public');
        }

        // 5. --------- SIMPAN KE DATABASE ---------
        $leaveApplication = LeaveApplication::create([
            'user_id' => $user->id,
            'leave_type' => $request->leave_type,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => $totalDays,
            'reason' => $request->reason,
            'address_during_leave' => $request->address_during_leave,
            'emergency_contact' => $request->emergency_contact,
            'attachment_path' => $attachmentPath,
            'status' => 'pending', // Status awal selalu 'pending'
        ]);

        // 6. --------- KURANGI KUOTA CUTI TAHUNAN ---------
        if ($leaveApplication->leave_type == 'tahunan') {
            $user->decrement('annual_leave_quota', $totalDays);
        }

        // 7. --------- ALIHKAN KE DASHBOARD ---------
        return redirect()->route('dashboard')->with('success', 'Pengajuan cuti Anda telah berhasil dikirim.');
    }

    public function showVerificationList()
    {
        $user = Auth::user();
        $pendingApplications = collect(); // Gunakan 'collect()' agar $pendingApplications selalu ada

        if ($user->role == 'ketua_divisi') {
            // 1. Ambil ID semua anggota di divisi si Ketua
            // Pastikan Anda 'use App\Models\User;' di atas file
            $teamMemberIds = User::where('division_id', $user->division_id)
                                 ->pluck('id');

            // 2. Ambil semua cuti 'pending' HANYA dari anggota tim-nya
            $pendingApplications = LeaveApplication::with('applicant')
                                        ->whereIn('user_id', $teamMemberIds)
                                        ->where('status', 'pending')
                                        ->latest()
                                        ->get();

        } elseif ($user->role == 'hrd') {
            // 3. HRD melihat semua cuti yang 'approved_by_leader'
            // ATAU cuti 'pending' dari Ketua Divisi
            $pendingApplications = LeaveApplication::with('applicant')
                                        ->where('status', 'approved_by_leader')
                                        ->orWhere(function($query) {
                                            $query->where('status', 'pending')
                                                  ->whereHas('applicant', function($q) {
                                                      $q->where('role', 'ketua_divisi');
                                                  });
                                        })
                                        ->latest()
                                        ->get();
        }

        // 4. Kirim data ke view
        return view('leave-applications.verification-list', compact('pendingApplications'));
    
    }

    public function showVerificationDetail(LeaveApplication $application)
{
    // Laravel otomatis mengambil data cuti (application) berdasarkan ID di URL
    // Kirim data ke view yang baru saja kita buat
    return view('leave-applications.verification-show', compact('application'));
}

/**
 * Menyetujui pengajuan cuti.
 */
public function approveLeave(Request $request, LeaveApplication $application)
{
    $user = Auth::user();

    if ($user->role == 'ketua_divisi') {
        // Alur Ketua Divisi: Ubah status dari 'pending' -> 'approved_by_leader'
        $application->update([
            'status' => 'approved_by_leader',
            'leader_approver_id' => $user->id,
            'leader_approval_at' => Carbon::now(),
        ]);
    } elseif ($user->role == 'hrd') {
        // Alur HRD: Ubah status dari 'approved_by_leader' -> 'approved_by_hrd' (Final)
        // Atau dari 'pending' -> 'approved_by_hrd' (jika pemohon adalah Ketua Divisi)
        $application->update([
            'status' => 'approved_by_hrd',
            'hrd_approver_id' => $user->id,
            'hrd_approval_at' => Carbon::now(),
        ]);
    }

    return redirect()->route('leave-verifications.index')->with('success', 'Cuti berhasil disetujui.');
}

/**
 * Menolak pengajuan cuti.
 */
public function rejectLeave(Request $request, LeaveApplication $application)
{
    $user = Auth::user();

    // 1. Validasi: Alasan penolakan wajib diisi
    $request->validate([
        'rejection_notes' => 'required|string', // Cukup wajib diisi    
    ]);

    $status = '';
    $rejectionNotes = $request->rejection_notes;

    if ($user->role == 'ketua_divisi') {
        $status = 'rejected_by_leader';
        // Simpan catatan penolakan dari leader
        $application->update([
            'status' => $status,
            'leader_rejection_notes' => $rejectionNotes,
            'leader_approver_id' => $user->id, // Catat siapa yang menolak
        ]);
    } elseif ($user->role == 'hrd') {
        $status = 'rejected_by_hrd';
        // Simpan catatan penolakan dari HRD
        $application->update([
            'status' => $status,
            'hrd_rejection_notes' => $rejectionNotes,
            'hrd_approver_id' => $user->id, // Catat siapa yang menolak
        ]);
    }

    // 2. PENTING: Kembalikan kuota cuti jika yang ditolak adalah 'Cuti Tahunan'
    if ($application->leave_type == 'tahunan') {
        $applicant = $application->applicant; // Ambil data si pemohon
        $applicant->increment('annual_leave_quota', $application->total_days); // Tambah kuotanya kembali
    }

    return redirect()->route('leave-verifications.index')->with('success', 'Cuti telah ditolak.');
    }

    /**
 * Membatalkan pengajuan cuti oleh pemohon.
 */
    public function cancelLeave(LeaveApplication $application)
    {
        // 1. Jaring Pengaman: Pastikan pemohon adalah user yang sedang login
        if ($application->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak berhak membatalkan pengajuan ini.');
        }

        // 2. Jaring Pengaman: Hanya boleh membatalkan jika statusnya masih 'pending'
        if ($application->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengajuan ini sudah diproses (Approve/Reject) dan tidak bisa dibatalkan.');
        }

        // 3. PENTING: Kembalikan kuota cuti jika jenisnya Tahunan
        if ($application->leave_type === 'tahunan') {
            $application->applicant->increment('annual_leave_quota', $application->total_days);
        }

        // 4. Update status menjadi 'cancelled'
        $application->update([
            'status' => 'cancelled',
            'cancellation_reason' => 'Dibatalkan oleh pemohon.', // Bisa ditambahkan form jika mau, tapi kita buat sederhana
        ]);

        return redirect()->route('leave-applications.index')->with('success', 'Pengajuan cuti berhasil dibatalkan dan kuota dikembalikan.');
    }
}

?>