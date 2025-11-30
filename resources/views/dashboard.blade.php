@if(Auth::user()->role == 'admin')
    @include('dashboard.admin')
@elseif(Auth::user()->role == 'hrd')
    @include('dashboard.hrd')
@elseif(Auth::user()->role == 'ketua_divisi')
    @include('dashboard.ketua_divisi')
@else
    @include('dashboard.karyawan')
@endif