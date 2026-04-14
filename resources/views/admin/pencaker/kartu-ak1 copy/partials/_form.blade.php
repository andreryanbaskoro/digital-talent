@php
$isEdit = isset($ak1);
$profil = $profil ?? null;
@endphp

<div class="row">

    {{-- DATA PENCARI KERJA --}}
    @include('admin.pencaker.kartu-ak1.partials._data_pencaker', [
    'profil' => $profil
    ])

    {{-- UPLOAD DOKUMEN --}}
    @include('admin.pencaker.kartu-ak1.partials._upload_dokumen', [
    'ak1' => $ak1 ?? null,
    'isEdit' => $isEdit
    ])

    {{-- KETERAMPILAN --}}
    @include('admin.pencaker.kartu-ak1.partials._keterampilan', [
    'keterampilanItems' => $keterampilanItems ?? [],
    'isEdit' => $isEdit
    ])

</div>