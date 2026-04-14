@php
$role = auth()->user()->peran;
// contoh: /admin/disnaker/dashboard → hasil: disnaker
@endphp

@if($role == 'disnaker')
@include('partials.admin.disnaker.sidebar')
@elseif($role == 'perusahaan')
@include('partials.admin.perusahaan.sidebar')
@elseif($role == 'pencaker')
@include('partials.admin.pencaker.sidebar')
@endif