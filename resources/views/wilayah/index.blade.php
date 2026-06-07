@extends('layouts.app')

@section('content')
<div class="page-header flex-wrap">
    <div class="header-left">
        <h3 class="page-title text-primary fw-bold">
            <span class="page-title-icon bg-gradient-primary text-white me-2 shadow">
                <i class="mdi mdi-map-marker-multiple menu-icon"></i>
            </span>
            Wilayah Administrasi Indonesia
        </h3>
        <p class="text-muted small mb-0 mt-1">
            Halaman ini digunakan untuk memilih Provinsi, Kota/Kabupaten, Kecamatan, dan Kelurahan/Desa secara bertingkat.
        </p>
    </div>
</div>

@if($provinces->isEmpty())
    <div class="alert alert-warning">
        Data provinsi belum tersedia. Jalankan migration dan import CSV wilayah terlebih dahulu.
    </div>
@endif

<div class="row">
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-2">Versi AJAX jQuery</h4>
                <p class="text-muted small">Menggunakan event change dan request $.ajax().</p>

                <div class="form-group">
                    <label for="jq_province">Provinsi</label>
                    <select id="jq_province" class="form-control">
                        <option value="">Pilih Provinsi</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="jq_regency">Kota/Kabupaten</label>
                    <select id="jq_regency" class="form-control" disabled>
                        <option value="">Pilih Kota/Kabupaten</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="jq_district">Kecamatan</label>
                    <select id="jq_district" class="form-control" disabled>
                        <option value="">Pilih Kecamatan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="jq_village">Kelurahan/Desa</label>
                    <select id="jq_village" class="form-control" disabled>
                        <option value="">Pilih Kelurahan/Desa</option>
                    </select>
                </div>

                <div id="jq_result" class="alert alert-info mt-4 mb-0">
                    Pilih wilayah sampai level Kelurahan/Desa untuk melihat ringkasan.
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-2">Versi Axios</h4>
                <p class="text-muted small">Menggunakan event change dan request axios.get().</p>

                <div class="form-group">
                    <label for="axios_province">Provinsi</label>
                    <select id="axios_province" class="form-control">
                        <option value="">Pilih Provinsi</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="axios_regency">Kota/Kabupaten</label>
                    <select id="axios_regency" class="form-control" disabled>
                        <option value="">Pilih Kota/Kabupaten</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="axios_district">Kecamatan</label>
                    <select id="axios_district" class="form-control" disabled>
                        <option value="">Pilih Kecamatan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="axios_village">Kelurahan/Desa</label>
                    <select id="axios_village" class="form-control" disabled>
                        <option value="">Pilih Kelurahan/Desa</option>
                    </select>
                </div>

                <div id="axios_result" class="alert alert-info mt-4 mb-0">
                    Pilih wilayah sampai level Kelurahan/Desa untuk melihat ringkasan.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-page')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    $(function () {
        const messages = {
            regency: 'Pilih Kota/Kabupaten',
            district: 'Pilih Kecamatan',
            village: 'Pilih Kelurahan/Desa',
            loading: 'Memuat data...'
        };

        function selectedText(selector) {
            const text = $(selector + ' option:selected').text();
            return $(selector).val() ? text : '-';
        }

        function resetJqSelect(selector, placeholder, disabled = true) {
            $(selector).empty().append(new Option(placeholder, '')).prop('disabled', disabled);
        }

        function fillJqSelect(selector, items, placeholder) {
            resetJqSelect(selector, placeholder, false);
            items.forEach(function (item) {
                $(selector).append(new Option(item.name, item.id));
            });
        }

        function setJqLoading(selector) {
            $(selector).empty().append(new Option(messages.loading, '')).prop('disabled', true);
        }

        function showJqResult(message = null, isError = false) {
            const $result = $('#jq_result');

            if (message) {
                $result.removeClass('alert-info alert-danger').addClass(isError ? 'alert-danger' : 'alert-info').text(message);
                return;
            }

            $result.removeClass('alert-danger').addClass('alert-info').html(
                '<strong>Hasil pilihan:</strong><br>' +
                'Provinsi: ' + selectedText('#jq_province') + '<br>' +
                'Kota/Kabupaten: ' + selectedText('#jq_regency') + '<br>' +
                'Kecamatan: ' + selectedText('#jq_district') + '<br>' +
                'Kelurahan/Desa: ' + selectedText('#jq_village')
            );
        }

        $('#jq_province').on('change', function () {
            const provinceId = $(this).val();
            resetJqSelect('#jq_regency', messages.regency);
            resetJqSelect('#jq_district', messages.district);
            resetJqSelect('#jq_village', messages.village);
            showJqResult('Pilih wilayah sampai level Kelurahan/Desa untuk melihat ringkasan.');

            if (!provinceId) {
                return;
            }

            setJqLoading('#jq_regency');

            $.ajax({
                url: '{{ url('/wilayah/regencies') }}/' + encodeURIComponent(provinceId),
                method: 'GET',
                success: function (response) {
                    fillJqSelect('#jq_regency', response.data || [], messages.regency);
                },
                error: function () {
                    resetJqSelect('#jq_regency', messages.regency);
                    showJqResult('Gagal mengambil data Kota/Kabupaten.', true);
                }
            });
        });

        $('#jq_regency').on('change', function () {
            const regencyId = $(this).val();
            resetJqSelect('#jq_district', messages.district);
            resetJqSelect('#jq_village', messages.village);

            if (!regencyId) {
                showJqResult('Pilih Kota/Kabupaten sebelum memilih Kecamatan.');
                return;
            }

            setJqLoading('#jq_district');

            $.ajax({
                url: '{{ url('/wilayah/districts') }}/' + encodeURIComponent(regencyId),
                method: 'GET',
                success: function (response) {
                    fillJqSelect('#jq_district', response.data || [], messages.district);
                },
                error: function () {
                    resetJqSelect('#jq_district', messages.district);
                    showJqResult('Gagal mengambil data Kecamatan.', true);
                }
            });
        });

        $('#jq_district').on('change', function () {
            const districtId = $(this).val();
            resetJqSelect('#jq_village', messages.village);

            if (!districtId) {
                showJqResult('Pilih Kecamatan sebelum memilih Kelurahan/Desa.');
                return;
            }

            setJqLoading('#jq_village');

            $.ajax({
                url: '{{ url('/wilayah/villages') }}/' + encodeURIComponent(districtId),
                method: 'GET',
                success: function (response) {
                    fillJqSelect('#jq_village', response.data || [], messages.village);
                },
                error: function () {
                    resetJqSelect('#jq_village', messages.village);
                    showJqResult('Gagal mengambil data Kelurahan/Desa.', true);
                }
            });
        });

        $('#jq_village').on('change', function () {
            if ($(this).val()) {
                showJqResult();
            }
        });

        const axiosProvince = document.getElementById('axios_province');
        const axiosRegency = document.getElementById('axios_regency');
        const axiosDistrict = document.getElementById('axios_district');
        const axiosVillage = document.getElementById('axios_village');
        const axiosResult = document.getElementById('axios_result');

        function resetAxiosSelect(select, placeholder, disabled = true) {
            select.innerHTML = '';
            select.appendChild(new Option(placeholder, ''));
            select.disabled = disabled;
        }

        function fillAxiosSelect(select, items, placeholder) {
            resetAxiosSelect(select, placeholder, false);
            items.forEach(function (item) {
                select.appendChild(new Option(item.name, item.id));
            });
        }

        function setAxiosLoading(select) {
            select.innerHTML = '';
            select.appendChild(new Option(messages.loading, ''));
            select.disabled = true;
        }

        function getAxiosText(select) {
            return select.value ? select.options[select.selectedIndex].text : '-';
        }

        function showAxiosResult(message = null, isError = false) {
            axiosResult.classList.toggle('alert-danger', isError);
            axiosResult.classList.toggle('alert-info', !isError);

            if (message) {
                axiosResult.textContent = message;
                return;
            }

            axiosResult.innerHTML =
                '<strong>Hasil pilihan:</strong><br>' +
                'Provinsi: ' + getAxiosText(axiosProvince) + '<br>' +
                'Kota/Kabupaten: ' + getAxiosText(axiosRegency) + '<br>' +
                'Kecamatan: ' + getAxiosText(axiosDistrict) + '<br>' +
                'Kelurahan/Desa: ' + getAxiosText(axiosVillage);
        }

        axiosProvince.addEventListener('change', function () {
            const provinceId = axiosProvince.value;
            resetAxiosSelect(axiosRegency, messages.regency);
            resetAxiosSelect(axiosDistrict, messages.district);
            resetAxiosSelect(axiosVillage, messages.village);
            showAxiosResult('Pilih wilayah sampai level Kelurahan/Desa untuk melihat ringkasan.');

            if (!provinceId) {
                return;
            }

            setAxiosLoading(axiosRegency);

            axios.get('{{ url('/wilayah/regencies') }}/' + encodeURIComponent(provinceId))
                .then(function (response) {
                    fillAxiosSelect(axiosRegency, response.data.data || [], messages.regency);
                })
                .catch(function () {
                    resetAxiosSelect(axiosRegency, messages.regency);
                    showAxiosResult('Gagal mengambil data Kota/Kabupaten.', true);
                });
        });

        axiosRegency.addEventListener('change', function () {
            const regencyId = axiosRegency.value;
            resetAxiosSelect(axiosDistrict, messages.district);
            resetAxiosSelect(axiosVillage, messages.village);

            if (!regencyId) {
                showAxiosResult('Pilih Kota/Kabupaten sebelum memilih Kecamatan.');
                return;
            }

            setAxiosLoading(axiosDistrict);

            axios.get('{{ url('/wilayah/districts') }}/' + encodeURIComponent(regencyId))
                .then(function (response) {
                    fillAxiosSelect(axiosDistrict, response.data.data || [], messages.district);
                })
                .catch(function () {
                    resetAxiosSelect(axiosDistrict, messages.district);
                    showAxiosResult('Gagal mengambil data Kecamatan.', true);
                });
        });

        axiosDistrict.addEventListener('change', function () {
            const districtId = axiosDistrict.value;
            resetAxiosSelect(axiosVillage, messages.village);

            if (!districtId) {
                showAxiosResult('Pilih Kecamatan sebelum memilih Kelurahan/Desa.');
                return;
            }

            setAxiosLoading(axiosVillage);

            axios.get('{{ url('/wilayah/villages') }}/' + encodeURIComponent(districtId))
                .then(function (response) {
                    fillAxiosSelect(axiosVillage, response.data.data || [], messages.village);
                })
                .catch(function () {
                    resetAxiosSelect(axiosVillage, messages.village);
                    showAxiosResult('Gagal mengambil data Kelurahan/Desa.', true);
                });
        });

        axiosVillage.addEventListener('change', function () {
            if (axiosVillage.value) {
                showAxiosResult();
            }
        });
    });
</script>
@endpush
