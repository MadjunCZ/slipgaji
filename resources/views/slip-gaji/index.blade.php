<!DOCTYPE html>
<html lang="id" data-bs-theme="{{ $darkMode ? 'dark' : 'light' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('logo-kemenag.png') }}">
    <title>{{ $title ?? 'Cari Slip Gaji' }} - SIPENA</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Flatpickr CSS (Month Picker) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-green: #198754;
            --primary-green-dark: #157347;
            --primary-green-light: #20c997;
        }
        
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }
        
        .dark body, [data-bs-theme="dark"] body {
            background: linear-gradient(135deg, #1a1d20 0%, #2d3339 100%);
        }
        
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-green-dark) 0%, #0a5828 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(25, 135, 84, 0.4);
        }
        
        .btn-primary:disabled {
            background: #6c757d;
            border: none;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.15);
        }
        
        .header-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-light) 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
        }
        
        .loading-spinner {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        
        .loading-spinner.show {
            display: flex;
        }
        
        .spinner-content {
            background: white;
            padding: 30px 50px;
            border-radius: 16px;
            text-align: center;
        }
        
        .result-card {
            border-left: 4px solid var(--primary-green);
            transition: all 0.3s ease;
        }
        
        .result-card:hover {
            border-left-width: 6px;
        }
        
        .badge-status {
            font-size: 0.75rem;
            padding: 6px 12px;
            border-radius: 20px;
        }
        
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }
        
        .table thead {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%);
            color: white;
        }
        
        .table thead th {
            border: none;
            font-weight: 600;
            padding: 15px;
        }
        
        .table tbody td {
            vertical-align: middle;
            padding: 15px;
        }
        
        .table tbody tr:hover {
            background-color: rgba(25, 135, 84, 0.05);
        }
        
        .toast-container {
            z-index: 10000;
        }
        
        @media (max-width: 768px) {
            .header-icon {
                width: 50px;
                height: 50px;
                font-size: 24px;
            }
            
            .card-body {
                padding: 1.25rem;
            }
        }
        
        .dark-mode-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
        
        .dark-mode-toggle:hover {
            transform: scale(1.1);
        }
        
        .progress-bar-container {
            display: none;
            height: 4px;
            background: #e9ecef;
            border-radius: 2px;
            overflow: hidden;
        }
        
        .progress-bar-container.show {
            display: block;
        }
        
        .progress-bar {
            background: linear-gradient(90deg, var(--primary-green) 0%, var(--primary-green-light) 100%);
            animation: progress 2s ease-in-out infinite;
        }
        
        @keyframes progress {
            0% { width: 0%; }
            50% { width: 70%; }
            100% { width: 100%; }
        }
    </style>
</head>
<body>
    <!-- Loading Spinner -->
    <div class="loading-spinner" id="loadingSpinner">
        <div class="spinner-content">
            <div class="spinner-border text-success mb-3" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h5 class="mb-1">Memproses...</h5>
            <p class="text-muted mb-0">Mohon tunggu sebentar</p>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="progress-bar-container" id="progressBar">
        <div class="progress-bar"></div>
    </div>

    <div class="container py-5">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center gap-3">
                    <img src="{{ asset('logo-kemenag.png') }}" alt="Logo Kemenag" style="height: 60px;">
                    <div>
                        <h2 class="mb-0 fw-bold">Cari Slip Gaji ASN</h2>
                        <p class="text-muted mb-0">Sistem Informasi Penggajian Negeri</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Form Card -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body p-4">
                        <form id="searchForm">
                            <div class="row g-4">
                                <!-- NIP -->
                                <div class="col-md-6">
                                    <label for="nip" class="form-label fw-semibold">
                                        <i class="bi bi-person-badge me-1"></i> Nomor Induk Pegawai (NIP)
                                    </label>
                                    <input type="text" 
                                    class="form-control @if($errors->has('nip')) is-invalid @endif" 
                                           id="nip" 
                                           name="nip" 
                                           placeholder="Contoh: 198501232010011001"
                                           maxlength="18"
                                           required>
                                    <div class="invalid-feedback" id="nipError"></div>
                                </div>

                                <!-- Unit Kerja -->
                                <div class="col-md-6">
                                    <label for="unit_kerja" class="form-label fw-semibold">
                                        <i class="bi bi-building me-1"></i> Unit / Satuan Kerja
                                    </label>
                                    <select class="form-select @if($errors->has('unit_kerja')) is-invalid @endif" 
                                            id="unit_kerja" 
                                            name="unit_kerja">
                                        <option value="">-- Pilih Unit Kerja --</option>
                                        @if(isset($unitKerja) && is_array($unitKerja))
                                            @foreach($unitKerja as $unit)
                                                @if(is_array($unit))
                                                    <option value="{{ $unit['kode'] ?? $unit['id'] ?? '' }}">
                                                        {{ $unit['nama'] ?? $unit['name'] ?? $unit['kode'] ?? $unit['id'] ?? 'Unit' }}
                                                    </option>
                                                @else
                                                    <option value="{{ $unit }}">{{ $unit }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                    <!-- Unit Kerja Custom Input (below select) -->
                                    <input type="text" 
                                           class="form-control mt-2" 
                                           id="unit_kerja_custom" 
                                           name="unit_kerja_custom"
                                           placeholder="Ketik nama unit kerja..."
                                           style="display: none;">
                                </div>

                                <!-- Periode Bulan & Tahun -->
                                <div class="col-md-4">
                                    <label for="periode" class="form-label fw-semibold">
                                        <i class="bi bi-calendar-month me-1"></i> Periode
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="periode" 
                                           name="periode" 
                                           placeholder="Pilih Bulan & Tahun"
                                           readonly
                                           required>
                                    <!-- Hidden fields for bulan and tahun -->
                                    <input type="hidden" id="bulan" name="bulan">
                                    <input type="hidden" id="tahun" name="tahun">
                                </div>

                                <!-- Kepentingan -->
                                <div class="col-md-4">
                                    <label for="tujuan_unduh" class="form-label fw-semibold">
                                        <i class="bi bi-flag me-1"></i> Kepentingan / Tujuan
                                    </label>
                                    <select class="form-select @if($errors->has('tujuan_unduh')) is-invalid @endif" 
                                            id="tujuan_unduh" 
                                            name="tujuan_unduh">
                                        <option value="">-- Pilih Kepentingan --</option>
                                        @if(isset($tujuanUnduh) && is_array($tujuanUnduh))
                                            @foreach($tujuanUnduh as $tujuan)
                                                @if(is_array($tujuan))
                                                    <option value="{{ $tujuan['kode'] ?? $tujuan['id'] ?? '' }}">
                                                        {{ $tujuan['nama'] ?? $tujuan['name'] ?? $tujuan['kode'] ?? $tujuan['id'] ?? 'Tujuan' }}
                                                    </option>
                                                @else
                                                    <option value="{{ $tujuan }}">{{ $tujuan }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                    <!-- Kepentingan Custom Input (below select) -->
                                    <input type="text" 
                                           class="form-control mt-2" 
                                           id="tujuan_custom" 
                                           name="tujuan_custom"
                                           placeholder="Ketik kepentingan/tujuan..."
                                           style="display: none;">
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12 mt-4">
                                    <button type="submit" 
                                            class="btn btn-primary w-100" 
                                            id="btnSearch"
                                            style="padding: 15px 30px; font-size: 1.1rem;">
                                        <i class="bi bi-search me-2"></i>
                                        <span id="btnSearchText">Proses Pencarian</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Section -->
        <div class="row mt-5" id="resultsSection" style="display: none;">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-table me-2 text-success"></i>
                                Hasil Pencarian
                            </h5>
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="printResults()">
                                <i class="bi bi-printer"></i> Cetak
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="resultsTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pegawai</th>
                                        <th>NIP</th>
                                        <th>Unit Kerja</th>
                                        <th>Periode Gaji</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="resultsBody">
                                    <!-- Results will be populated here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- No Results Message -->
        <div class="row mt-5" id="noResultsSection" style="display: none;">
            <div class="col-12 text-center">
                <div class="card">
                    <div class="card-body py-5">
                        <i class="bi bi-inbox-fill text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-muted">Tidak Ada Data</h4>
                        <p class="text-muted">Data slip gaji tidak ditemukan untuk kriteria yang dipilih.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Download Modal -->
    <div class="modal fade" id="downloadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title">
                        <i class="bi bi-download text-success me-2"></i>
                        Download Slip Gaji
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Slip gaji ditemukan. Pilih tujuan pengunduhan:</p>
                    <input type="hidden" id="downloadSlipId">
                    <div class="mb-3">
                        <label for="downloadTujuan" class="form-label">Tujuan Pengunduhan</label>
                        <select class="form-select" id="downloadTujuan">
                            <option value="perbankan">Pengajuan Perbankan</option>
                            <option value="pinjaman">Pinjaman</option>
                            <option value="kredit">Kredit</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="btnDownload" onclick="downloadSlip()">
                        <i class="bi bi-download me-1"></i> Download PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="bi bi-bell me-2 text-success"></i>
                <strong class="me-auto" id="toastTitle">Notifikasi</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastMessage"></div>
        </div>
    </div>

    <!-- Dark Mode Toggle -->
    <button class="btn btn-dark dark-mode-toggle" onclick="toggleDarkMode()">
        <i class="bi bi-{{ $darkMode ? 'sun' : 'moon' }}" id="darkModeIcon"></i>
    </button>

    <!-- jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Flatpickr JS (Month Picker) -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
    
    <script>
        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2
            $('#unit_kerja').select2({
                placeholder: '-- Pilih Unit Kerja --',
                allowClear: true,
                theme: 'bootstrap4'
            });
            
            $('#tujuan_unduh').select2({
                placeholder: '-- Pilih Kepentingan --',
                allowClear: true,
                theme: 'bootstrap4'
            });
            
            // Initialize Flatpickr Month Picker
            flatpickr("#periode", {
                plugins: [
                    new monthSelectPlugin({
                        shorthand: true,
                        dateFormat: "F Y",
                        altInput: true,
                        altFormat: "F Y",
                    })
                ],
                defaultDate: new Date(),
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length > 0) {
                        const date = selectedDates[0];
                        document.getElementById('bulan').value = date.getMonth() + 1;
                        document.getElementById('tahun').value = date.getFullYear();
                    }
                }
            });
            
            // Set initial values
            const now = new Date();
            document.getElementById('bulan').value = now.getMonth() + 1;
            document.getElementById('tahun').value = now.getFullYear();
            
            // Form submit handler
            document.getElementById('searchForm').addEventListener('submit', handleSearch);
            
            // NIP input validation
            document.getElementById('nip').addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length > 18) {
                    this.value = this.value.slice(0, 18);
                }
            });
            
            // Toggle unit_kerja: show input below select (Select2 event)
            $('#unit_kerja').on('select2:select', function(e) {
                const customInput = document.getElementById('unit_kerja_custom');
                const selectedValue = e.params.data.id;
                
                if (selectedValue === 'Lainnya') {
                    customInput.style.display = 'block';
                    customInput.focus();
                } else {
                    customInput.style.display = 'none';
                    customInput.value = '';
                }
            });
            
            // Toggle tujuan_unduh: show input below select (Select2 event)
            $('#tujuan_unduh').on('select2:select', function(e) {
                const customInput = document.getElementById('tujuan_custom');
                const selectedValue = e.params.data.id;
                
                if (selectedValue === 'Lainnya') {
                    customInput.style.display = 'block';
                    customInput.focus();
                } else {
                    customInput.style.display = 'none';
                    customInput.value = '';
                }
            });
        });

        // Handle Search
        async function handleSearch(e) {
            e.preventDefault();
            
            const btn = document.getElementById('btnSearch');
            const btnText = document.getElementById('btnSearchText');
            const spinner = document.getElementById('loadingSpinner');
            
            // Get form data - handle custom inputs
            let unitKerja = document.getElementById('unit_kerja').value;
            const unitKerjaCustom = document.getElementById('unit_kerja_custom').value;
            if (unitKerja === 'Lainnya') {
                unitKerja = unitKerjaCustom || 'Lainnya';
            }
            
            let tujuanUnduh = document.getElementById('tujuan_unduh').value;
            const tujuanCustom = document.getElementById('tujuan_custom').value;
            if (tujuanUnduh === 'Lainnya') {
                tujuanUnduh = tujuanCustom || 'Lainnya';
            }
            
            const formData = {
                nip: document.getElementById('nip').value,
                bulan: document.getElementById('bulan').value,
                tahun: document.getElementById('tahun').value,
                unit_kerja: unitKerja,
                tujuan_unduh: tujuanUnduh,
            };
            
            // Validation
            if (!formData.nip) {
                showToast('Error', 'NIP wajib diisi', 'danger');
                return;
            }
            
            if (!formData.bulan || !formData.tahun) {
                showToast('Error', 'Bulan dan Tahun wajib dipilih', 'danger');
                return;
            }
            
            // Validation for custom inputs
            const unitKerjaSelect = document.getElementById('unit_kerja').value;
            if (unitKerjaSelect === 'Lainnya' && !document.getElementById('unit_kerja_custom').value.trim()) {
                showToast('Error', 'Nama Unit Kerja wajib diisi', 'danger');
                document.getElementById('unit_kerja_custom').focus();
                return;
            }
            
            const tujuanSelect = document.getElementById('tujuan_unduh').value;
            if (tujuanSelect === 'Lainnya' && !document.getElementById('tujuan_custom').value.trim()) {
                showToast('Error', 'Kepentingan/Tujuan wajib diisi', 'danger');
                document.getElementById('tujuan_custom').focus();
                return;
            }
            
            // Show loading
            btn.disabled = true;
            btnText.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses & Mengunduh...';
            spinner.classList.add('show');
            
            try {
                // First search - request PDF directly
                const searchResponse = await fetch('{{ route("slip-gaji.search") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/pdf',
                    },
                    body: JSON.stringify(formData)
                });
                
                // Check content type
                const contentType = searchResponse.headers.get('content-type');
                
                if (contentType && contentType.includes('application/pdf')) {
                    // Response is PDF - download directly
                    const blob = await searchResponse.blob();
                    const filename = 'slip_gaji_' + formData.nip + '_' + formData.tahun + ('0' + formData.bulan).slice(-2) + '.pdf';
                    
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                    
                    showToast('Berhasil!', 'Slip gaji berhasil diunduh', 'success');
                    return;
                }
                
                // Response is JSON - parse normally
                const searchResult = await searchResponse.json();
                
                if (!searchResult.success || !searchResult.data || searchResult.data.length === 0) {
                    showToast('Gagal', searchResult.message || 'Data slip gaji tidak ditemukan', 'danger');
                    document.getElementById('resultsSection').style.display = 'none';
                    document.getElementById('noResultsSection').style.display = 'block';
                    return;
                }
                
                // Get first item for download
                const firstItem = Array.isArray(searchResult.data) ? searchResult.data[0] : searchResult.data;
                const slipId = firstItem.id || firstItem.slip_id || firstItem.nip || formData.nip;
                
                // Directly download
                const downloadResponse = await fetch(`{{ route("slip-gaji.download", ["slipId" => "__slipId__"]) }}`.replace('__slipId__', slipId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ tujuan_unduh: formData.tujuan_unduh })
                });
                
                const downloadResult = await downloadResponse.json();
                
                if (downloadResult.success && downloadResult.download_url) {
                    // Auto download file
                    const a = document.createElement('a');
                    a.href = downloadResult.download_url;
                    a.download = downloadResult.filename || 'slip_gaji_' + slipId + '.pdf';
                    a.target = '_blank';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    
                    showToast('Berhasil!', 'Slip gaji berhasil diunduh', 'success');
                } else {
                    showToast('Gagal', downloadResult.message || 'Gagal mengunduh slip gaji', 'danger');
                }
            } catch (error) {
                console.error('Search error:', error);
                showToast('Error', 'Terjadi kesalahan sistem: ' + error.message, 'danger');
            } finally {
                btn.disabled = false;
                btnText.innerHTML = '<i class="bi bi-search me-2"></i>Proses Pencarian';
                spinner.classList.remove('show');
            }
        }

        // Display Results
        function displayResults(data) {
            const resultsSection = document.getElementById('resultsSection');
            const noResultsSection = document.getElementById('noResultsSection');
            const tbody = document.getElementById('resultsBody');
            
            if (!data || (Array.isArray(data) && data.length === 0)) {
                resultsSection.style.display = 'none';
                noResultsSection.style.display = 'block';
                return;
            }
            
            resultsSection.style.display = 'block';
            noResultsSection.style.display = 'none';
            
            const items = Array.isArray(data) ? data : [data];
            
            tbody.innerHTML = items.map((item, index) => `
                <tr class="result-card">
                    <td>${index + 1}</td>
                    <td>
                        <strong>${item.nama || item.name || '-'}</strong>
                    </td>
                    <td>${item.nip || '-'}</td>
                    <td>${item.unit_kerja || item.unit || '-'}</td>
                    <td>${item.periode || item.bulan + '/' + item.tahun || '-'}</td>
                    <td>
                        <span class="badge bg-success badge-status">
                            <i class="bi bi-check-circle me-1"></i> Tersedia
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-success" onclick="openDownloadModal('${item.id || item.slip_id || item.nip}')">
                            <i class="bi bi-download me-1"></i> Download
                        </button>
                    </td>
                </tr>
            `).join('');
            
            // Auto-download first result if available
            if (items.length > 0) {
                const firstItem = items[0];
                const slipId = firstItem.id || firstItem.slip_id || firstItem.nip;
                
                // Show download modal for first item
                setTimeout(() => {
                    openDownloadModal(slipId);
                }, 500);
            }
        }

        // Open Download Modal
        function openDownloadModal(slipId) {
            document.getElementById('downloadSlipId').value = slipId;
            const modal = new bootstrap.Modal(document.getElementById('downloadModal'));
            modal.show();
        }

        // Download Slip
        async function downloadSlip() {
            const slipId = document.getElementById('downloadSlipId').value;
            const tujuan = document.getElementById('downloadTujuan').value;
            const btn = document.getElementById('btnDownload');
            
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengunduh...';
            
            try {
                const response = await fetch(`{{ route("slip-gaji.download", ["slipId" => "__slipId__"]) }}`.replace('__slipId__', slipId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ tujuan_unduh: tujuan })
                });
                
                const result = await response.json();
                
                if (result.success && result.download_url) {
                    showToast('Berhasil', 'Download slip gaji siap', 'success');
                    
                    // Auto download
                    const a = document.createElement('a');
                    a.href = result.download_url;
                    a.download = result.filename || 'slip_gaji.pdf';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    
                    bootstrap.Modal.getInstance(document.getElementById('downloadModal')).hide();
                } else {
                    showToast('Gagal', result.message || 'Download gagal', 'danger');
                }
            } catch (error) {
                console.error('Download error:', error);
                showToast('Error', 'Terjadi kesalahan saat download', 'danger');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-download me-1"></i> Download PDF';
            }
        }

        // Toggle Dark Mode
        async function toggleDarkMode() {
            const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
            const newTheme = isDark ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-bs-theme', newTheme);
            document.getElementById('darkModeIcon').className = `bi bi-${isDark ? 'moon' : 'sun'}`;
            
            try {
                await fetch('{{ route("slip-gaji.dark-mode") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ dark: !isDark })
                });
            } catch (error) {
                console.error('Dark mode toggle error:', error);
            }
        }

        // Print Results
        function printResults() {
            window.print();
        }

        // Show Toast
        function showToast(title, message, type = 'info') {
            const toast = document.getElementById('toast');
            const toastTitle = document.getElementById('toastTitle');
            const toastMessage = document.getElementById('toastMessage');
            
            toastTitle.textContent = title;
            toastMessage.textContent = message;
            
            toast.className = `toast bg-${type} text-white`;
            toast.querySelector('.toast-header').className = `toast-header bg-${type} text-white`;
            
            const bsToast = new bootstrap.Toast(toast, { delay: 4000 });
            bsToast.show();
        }
    </script>
</body>
</html>
