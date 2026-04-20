@extends('layouts.app-admin')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $title ?? 'Tambah Lowongan' }}</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @include('admin.perusahaan.lowongan.partials.alerts')
            @include('admin.perusahaan.lowongan.partials._form')
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admin-js/modal.js') }}"></script>
<script src="{{ asset('admin-js/alerts.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const wrapper = document.getElementById('skill-wrapper');
        const btnAdd = document.getElementById('btn-add-skill');

        if (!wrapper || !btnAdd) return;

        let index = Number(wrapper.dataset.count) || 0;

        btnAdd.addEventListener('click', function() {
            const html = `
                <div class="row mb-2 skill-item align-items-center">
                    <div class="col-md-6">
                        <input type="text" name="sub_kriteria[${index}][nama]" class="form-control" placeholder="Skill">
                    </div>
                    <div class="col-md-4">
                        <select name="sub_kriteria[${index}][nilai_target]" class="form-control">
                            <option value="">-- Pilih Level --</option>
                            <option value="5">5 - Sangat ahli</option>
                            <option value="4">4 - Mahir</option>
                            <option value="3">3 - Cukup</option>
                            <option value="2">2 - Dasar</option>
                            <option value="1">1 - Tidak bisa</option>
                        </select>
                    </div>
                    <div class="col-md-2 text-right">
                        <button type="button" class="btn btn-danger btn-remove">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;

            wrapper.insertAdjacentHTML('beforeend', html);
            index++;
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-remove')) {
                e.target.closest('.skill-item').remove();
            }
        });
    });
</script>
@endpush