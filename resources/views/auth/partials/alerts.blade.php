@if(session('success'))
<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
    <i class="fas fa-check-circle mr-1"></i>
    {{ session('success') }}

    @if(session('detected_role'))
    <hr class="my-1">
    <small class="text-muted">
        Terdeteksi sebagai: <b>{{ ucfirst(session('detected_role')) }}</b>
    </small>
    @endif

    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
@endif


@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
    <i class="fas fa-exclamation-circle mr-1"></i>
    {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
@endif


@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
    <i class="fas fa-times-circle mr-1"></i>
    <b>Terjadi kesalahan:</b>

    <ul class="mb-0 mt-1">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>

    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
@endif


@if(session('info'))
<div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
    <i class="fas fa-info-circle mr-1"></i>
    {{ session('info') }}
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
@endif

