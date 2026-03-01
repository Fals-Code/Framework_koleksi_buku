@extends('layouts.app')

@section('content')
<style>
.table-modern thead th {
border-top: 0;
border-bottom-width: 1px;
font-weight: 700;
font-size: 0.75rem;
text-transform: uppercase;
color: #4b49ac;
background-color: #f8f9fa;
padding: 15px !important;
}

.table-modern tbody td {
padding: 18px 15px !important;
vertical-align: middle;
font-size: 0.875rem;
}

.book-title-cell {
font-weight: 600;
color: #343a40;
line-height: 1.4;
}

.badge-category {
padding: 0.5rem 0.8rem;
border-radius: 6px;
font-weight: 500;
letter-spacing: 0.3px;
}

.btn-action {
width: 35px;
height: 35px;
padding: 0;
display: inline-flex;
align-items: center;
justify-content: center;
border-radius: 8px !important;
transition: all 0.2s;
}

.btn-action:hover {
transform: translateY(-2px);
}

.code-tag {
background: #f0f0ff;
color: #b66dff;
padding: 4px 8px;
border-radius: 4px;
font-family: 'Courier New', Courier, monospace;
font-weight: bold;
}

.form-check-input-custom {
width: 18px;
height: 18px;
cursor: pointer;
}

.col-checkbox {
display: none;
}

</style>

<div class="page-header flex-wrap">
<div class="header-left">
<h3 class="page-title text-primary fw-bold">
<span class="page-title-icon bg-gradient-primary text-white me-2 shadow">
<i class="mdi mdi-book-open-page-variant menu-icon"></i>
</span> Manajemen Pustaka
</h3>
<p class="text-muted small mb-0 mt-1">Sistem informasi koleksi buku digital perpustakaan.</p>
</div>
<div class="header-right d-flex align-items-center mt-md-0 mt-3 gap-2">
<select id="filterKategori" class="form-select form-select-sm border-primary shadow-sm" style="width: 180px;">
<option value="">Semua Kategori</option>
@foreach($kategoris as $kat)
<option value="{{ $kat->nama_kategori }}">{{ $kat->nama_kategori }}</option>
@endforeach
</select>

    <button class="btn btn-gradient-warning btn-icon-text shadow-sm" id="btnEnableSelect">
        <i class="mdi mdi-checkbox-marked-outline btn-icon-prepend"></i> Mode Pilih
    </button>

    <div id="printActionGroup" class="d-none animate__animated animate__fadeIn">
        <button class="btn btn-gradient-info btn-icon-text shadow-sm" id="btnPrintSelected">
            <i class="mdi mdi-printer btn-icon-prepend"></i> Cetak (<span id="countSelected">0</span>)
        </button>
        <button class="btn btn-gradient-danger btn-icon-text shadow-sm" id="btnBulkDelete">
            <i class="mdi mdi-delete-sweep btn-icon-prepend"></i> Hapus Masal
        </button>
        <button class="btn btn-light btn-icon-text shadow-sm" id="btnCancelSelect">Batal</button>
    </div>

    <a href="{{ route('buku.create') }}" class="btn btn-gradient-primary btn-icon-text shadow-sm" onclick="btnLoading(this)">
        <i class="mdi mdi-book-plus btn-icon-prepend"></i> Registrasi Baru
    </a>
</div>
</div>

<div class="row">
<div class="col-lg-12 grid-margin stretch-card">
<div class="card border-0 shadow-sm">
<div class="card-body">

<div class="d-flex justify-content-between align-items-center mb-4">

<h4 class="card-title mb-0">Database Judul Terdaftar</h4>

<div class="search-box">

<div class="input-group input-group-sm">

<input type="text" id="searchInput" class="form-control border-primary" placeholder="Cari judul atau pengarang...">

<button class="btn btn-primary btn-sm"><i class="mdi mdi-magnify"></i></button>

</div>

</div>

</div>



            <div class="table-responsive">

                <table class="table table-hover table-modern">

                    <thead>

                        <tr>

                            <th class="col-checkbox" style="width: 5%"><input type="checkbox" id="checkAll" class="form-check-input-custom"></th>

                            <th class="col-no" style="width: 5%">No</th>

                            <th style="width: 15%">Kode Buku</th>

                            <th style="width: 25%">Detail Buku</th>

                            <th style="width: 20%">Penulis</th>

                            <th style="width: 15%">Kategori</th>

                            <th class="col-opsi text-center" style="width: 15%">Opsi</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($bukus as $key => $buku)

                        <tr>

                            <td class="col-checkbox"><input type="checkbox" class="sub_chk form-check-input-custom" data-id="{{ $buku->id }}"></td>

                            <td class="col-no">{{ $key+1 }}</td>

                            <td><span class="code-tag">{{ $buku->kode }}</span></td>

                            <td>

                                <div class="book-title-cell text-wrap" style="max-width: 280px;">

                                    {{ $buku->judul }}

                                </div>

                            </td>

                            <td>

                                <div class="d-flex align-items-center">

                                    <div class="bg-light-info rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">

                                        <i class="mdi mdi-account text-info small"></i>

                                    </div>

                                    <span>{{ $buku->pengarang }}</span>

                                </div>

                            </td>

                            <td>

                                <span class="badge badge-category {{ $buku->kategori ? 'badge-gradient-info' : 'badge-gradient-secondary text-white' }}">

                                    <i class="mdi mdi-tag-outline me-1"></i>

                                    {{ $buku->kategori->nama_kategori ?? 'Uncategorized' }}

                                </span>

                            </td>

                            <td class="col-opsi text-center">

                                <div class="d-flex justify-content-center gap-2">

                                    <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-inverse-warning btn-action" title="Edit Data" onclick="btnLoading(this)">

                                        <i class="mdi mdi-pencil"></i>

                                    </a>

                                    <form action="{{ route('buku.destroy', $buku->id) }}" method="POST" onsubmit="return confirmDeleteBuku(this)">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit" class="btn btn-inverse-danger btn-action" title="Hapus Data">

                                            <i class="mdi mdi-trash-can"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="7" class="text-center py-5 text-muted">

                                <i class="mdi mdi-book-open-variant mdi-48px d-block mb-2 opacity-25"></i>

                                <p>Belum ada data buku dalam database ini.</p>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>



            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-3 border-top">

                <p class="text-muted small mb-3 mb-md-0">

                    Menampilkan <strong>{{ $bukus->count() }}</strong> entri buku dalam sistem.

                </p>

                <div class="pagination-container">

                    @if(method_exists($bukus, 'links'))

                        {{ $bukus->links() }}

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

</div>

@endsection



@push('script-page')

<script>
$(document).ready(function() {
    $('#btnEnableSelect').on('click', function() {
        $(this).addClass('d-none');
        $('#printActionGroup').removeClass('d-none').addClass('d-flex gap-2');
        $('.col-opsi, .col-no').addClass('d-none');
        $('.col-checkbox').fadeIn().removeClass('d-none');
    });

    $('#btnCancelSelect').on('click', function() {
        $('#printActionGroup').removeClass('d-flex').addClass('d-none');
        $('#btnEnableSelect').removeClass('d-none');
        $('.col-checkbox').fadeOut().addClass('d-none');
        $('.col-opsi, .col-no').removeClass('d-none').fadeIn();
        $('.sub_chk, #checkAll').prop('checked', false);
        $('#countSelected').text('0');
    });

    $(document).on('change', '.sub_chk, #checkAll', function() {
        var count = $('.sub_chk:checked').length;
        $('#countSelected').text(count);
    });

    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $(".table-modern tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $('#filterKategori').on('change', function() {
        var selected = $(this).val().toLowerCase();
        $(".table-modern tbody tr").filter(function() {
            $(this).toggle($(this).find('td:nth-child(6)').text().toLowerCase().indexOf(selected) > -1);
        });
    });

    $('#checkAll').on('click', function() {
        $(".sub_chk").prop('checked', $(this).prop('checked'));
    });

    $('#btnPrintSelected').on('click', function() {
        var selectedIds = [];
        $(".sub_chk:checked").each(function() {
            selectedIds.push($(this).attr('data-id'));
        });

        if (selectedIds.length <= 0) {
            Swal.fire({ icon: 'warning', title: 'Pilih Buku', text: 'Silakan centang buku yang ingin dicetak labelnya.', confirmButtonColor: '#b66dff' });
        } else {
            var url = "{{ route('buku.cetak_label') }}?id=" + selectedIds.join(",");
            window.open(url, '_blank');
        }
    });

    $('#btnBulkDelete').on('click', function() {
        var selectedIds = [];
        $(".sub_chk:checked").each(function() {
            selectedIds.push($(this).attr('data-id'));
        });

        if (selectedIds.length <= 0) {
            Swal.fire({ icon: 'warning', title: 'Pilih Data!', text: 'Centang dulu buku yang mau dihapus.' });
            return;
        }

        Swal.fire({
            title: 'Hapus ' + selectedIds.length + ' Buku?',
            text: "Data yang dipilih akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus Semua!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('buku.bulkDelete') }}",
                    type: 'DELETE',
                    data: {
                        ids: selectedIds.join(","),
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire('Berhasil!', response.message, 'success').then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function () {
                        Swal.fire('Gagal!', 'Terjadi kesalahan sistem.', 'error');
                    }
                });
            }
        });
    });
});

function confirmDeleteBuku(form) {
    Swal.fire({
        title: 'Hapus Buku?',
        text: "Data buku akan dihapus secara permanen dari sistem!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#fe72af',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const btn = form.querySelector('button[type="submit"]');
            btnLoading(btn);
            form.submit();
        }
    });
    return false;
}
</script>

@endpush