@extends('layouts.app')

@section('title', 'Daftar Pengguna Sistem')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Daftar Pengguna Sistem (Admin & Staff)</h3>
        <p class="text-muted small mb-0">Data akun yang dibuat melalui Seeder Laravel (Slide 30)</p>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th>Nama Pengguna</th>
                    <th>Email Login</th>
                    <th>Role / Jabatan</th>
                    <th>Waktu Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @if($users->count() > 0)
                @foreach($users as $u)
                <tr>
                    <td class="text-muted">#{{ $u->id }}</td>
                    <td class="fw-bold">
                        <i class="bi bi-person-circle me-1 text-primary"></i> {{ $u->name }}
                    </td>
                    <td><span class="badge bg-light text-dark border font-monospace">{{ $u->email }}</span></td>
                    <td>
                        @if(stripos($u->name, 'admin') !== false || stripos($u->email, 'admin') !== false)
                            <span class="badge bg-danger">ADMINISTRATOR</span>
                        @else
                            <span class="badge bg-success">STAFF GUDANG</span>
                        @endif
                    </td>
                    <td class="small text-muted">{{ $u->created_at ? $u->created_at->format('d M Y, H:i') : '-' }}</td>
                </tr>
                @endforeach
                @else
                <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada akun pengguna.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white py-3">
        <div class="alert alert-secondary mb-0 small">
            <strong>Kredensial Akun Default (Dihasilkan oleh DatabaseSeeder):</strong><br>
            &bull; <strong>Admin:</strong> Email: <code>admin@inventory.com</code> | Password: <code>admin123</code><br>
            &bull; <strong>Staff:</strong> Email: <code>staff@inventory.com</code> | Password: <code>staff123</code>
        </div>
    </div>
</div>
@endsection
