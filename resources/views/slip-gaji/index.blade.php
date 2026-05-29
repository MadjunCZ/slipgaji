<!DOCTYPE html>
<html lang="id" data-bs-theme="{{ $darkMode ? 'dark' : 'light' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('logo-kemenag.png') }}">
    <title>{{ $title ?? ' Slip Gaji ASN Kementerian Agama Kabupaten Nganjuk' }}</title>
    
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #059669;
            --primary-dark: #047857;
            --primary-light: #10b981;
            --primary-glow: rgba(5, 150, 105, 0.15);
            --bg-gradient-start: #f0fdf4;
            --bg-gradient-end: #ecfdf5;
            --card-bg: rgba(255, 255, 255, 0.95);
            --text-primary: #064e3b;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --shadow-primary: 0 4px 14px 0 rgba(5, 150, 105, 0.35);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
            --radius-2xl: 24px;
        }

        [data-bs-theme="dark"] {
            --bg-gradient-start: #022c22;
            --bg-gradient-end: #064e3b;
            --card-bg: rgba(6, 78, 59, 0.5);
            --text-primary: #ecfdf5;
            --text-secondary: #9ca3af;
            --border-color: #374151;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.3);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.4);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.4);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
            min-height: 100vh;
            color: var(--text-primary);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Background Pattern */
        .bg-pattern {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(at 100% 0%, var(--primary-glow) 0%, transparent 50%),
                radial-gradient(at 0% 100%, var(--primary-glow) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .main-container {
            position: relative;
            z-index: 1;
            padding: 2rem 1rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .page-header {
            text-align: center;
            margin-bottom: 2.5rem;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header-content {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .logo-container {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: var(--radius-xl);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-primary);
            animation: pulse-glow 3s ease-in-out infinite;
        }

        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: var(--shadow-primary);
            }
            50% {
                box-shadow: 0 4px 20px 4px rgba(5, 150, 105, 0.4);
            }
        }

        .logo-container img {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }

        .header-text {
            text-align: center;
        }

        .header-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
            letter-spacing: -0.02em;
        }

        .header-subtitle {
            font-size: 1rem;
            color: var(--text-secondary);
            font-weight: 400;
        }

        .header-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: var(--primary-glow);
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--primary);
            margin-top: 0.5rem;
        }

        /* Form Card */
        .form-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-xl);
            padding: 2.5rem;
            animation: fadeInUp 0.6s ease-out 0.1s backwards;
            position: relative;
            overflow: hidden;
        }

        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--primary-light), var(--primary));
        }

        .form-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .form-title-icon {
            width: 36px;
            height: 36px;
            background: var(--primary-glow);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.625rem;
        }

        .form-label-icon {
            width: 20px;
            height: 20px;
            color: var(--primary);
        }

        .form-control, .form-select {
            height: 52px;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 0.875rem 1rem;
            font-size: 0.9375rem;
            font-family: inherit;
            background: white;
            color: var(--text-primary);
            transition: all 0.2s ease;
        }

        [data-bs-theme="dark"] .form-control, 
        [data-bs-theme="dark"] .form-select {
            background: var(--card-bg);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-glow);
            outline: none;
        }

        .form-control::placeholder {
            color: var(--text-secondary);
            opacity: 0.7;
        }

        .form-control.is-invalid, 
        .form-select.is-invalid {
            border-color: #ef4444;
        }

        .form-control.is-invalid:focus,
        .form-select.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15);
        }

        .invalid-feedback {
            font-size: 0.8125rem;
            color: #ef4444;
            margin-top: 0.375rem;
        }

        /* Select2 Custom */
        .select2-container--bootstrap4 .select2-selection {
            height: 52px !important;
            border: 2px solid var(--border-color) !important;
            border-radius: var(--radius-lg) !important;
            padding: 0.5rem 0.75rem !important;
        }

        .select2-container--bootstrap4 .select2-selection__rendered {
            line-height: 36px !important;
            font-size: 0.9375rem;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
            height: 50px !important;
        }

        .select2-container--bootstrap4.select2-container--focus .select2-selection,
        .select2-container--bootstrap4.select2-container--open .select2-selection {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 4px var(--primary-glow) !important;
        }

        .select2-dropdown {
            border: 2px solid var(--border-color) !important;
            border-radius: var(--radius-md) !important;
            box-shadow: var(--shadow-lg) !important;
        }

        .select2-container--bootstrap4 .select2-results__option {
            padding: 0.75rem 1rem !important;
        }

        .select2-container--bootstrap4 .select2-results__option--highlighted[aria-selected] {
            background: var(--primary) !important;
        }

        /* Flatpickr Custom */
        .flatpickr-calendar {
            border: 2px solid var(--border-color) !important;
            border-radius: var(--radius-lg) !important;
            box-shadow: var(--shadow-xl) !important;
        }

        .flatpickr-day.selected, 
        .flatpickr-day.startRange, 
        .flatpickr-day.endRange {
            background: var(--primary) !important;
            border-color: var(--primary) !important;
        }

        .flatpickr-day:hover {
            background: var(--primary-glow) !important;
        }

        .flatpickr-months .flatpickr-prev-month:hover,
        .flatpickr-months .flatpickr-next-month:hover {
            background: var(--primary-glow) !important;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            height: 56px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: var(--radius-lg);
            color: white;
            font-size: 1rem;
            font-weight: 600;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-primary);
            position: relative;
            overflow: hidden;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px 4px rgba(5, 150, 105, 0.4);
        }

        .btn-submit:hover::before {
            left: 100%;
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
            box-shadow: var(--shadow-md);
        }

        .btn-submit-icon {
            width: 24px;
            height: 24px;
        }

        /* Loading Spinner */
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .loading-overlay.show {
            display: flex;
        }

        .loading-card {
            background: white;
            border-radius: var(--radius-xl);
            padding: 2.5rem 3rem;
            text-align: center;
            box-shadow: var(--shadow-xl);
        }

        [data-bs-theme="dark"] .loading-card {
            background: var(--card-bg);
        }

        .loading-spinner {
            width: 56px;
            height: 56px;
            border: 4px solid var(--primary-glow);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1.5rem;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .loading-text {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        /* Results Section */
        .results-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-xl);
            overflow: hidden;
            margin-top: 2rem;
            animation: fadeInUp 0.5s ease-out;
        }

        .results-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .results-title {
            color: white;
            font-size: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0;
        }

        .results-table {
            margin: 0;
        }

        .results-table thead {
            background: var(--primary-glow);
        }

        .results-table thead th {
            color: var(--primary-dark);
            font-weight: 600;
            font-size: 0.8125rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem 1.25rem;
            border: none;
        }

        .results-table tbody td {
            padding: 1rem 1.25rem;
            vertical-align: middle;
            border-color: var(--border-color);
        }

        .results-table tbody tr:hover {
            background: var(--primary-glow);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            background: #dcfce7;
            color: #166534;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        [data-bs-theme="dark"] .status-badge {
            background: rgba(5, 150, 105, 0.2);
            color: #6ee7b7;
        }

        .btn-download {
            padding: 0.5rem 1rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-size: 0.8125rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-download:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            background: var(--primary-glow);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: var(--primary);
            font-size: 2rem;
        }

        .empty-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .empty-text {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        /* Modal */
        .modal-content {
            border: none;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border-radius: var(--radius-xl) var(--radius-xl) 0 0;
            padding: 1.25rem 1.5rem;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid var(--border-color);
            padding: 1rem 1.5rem;
        }

        .btn-modal-download {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: var(--radius-md);
            color: white;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
        }

        .btn-modal-download:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            color: white;
        }

        /* Toast */
        .toast-container {
            z-index: 10000;
        }

        .toast {
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xl);
        }

        .toast.bg-success {
            background: var(--primary) !important;
        }

        /* Dark Mode Toggle */
        .dark-toggle {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            width: 52px;
            height: 52px;
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: var(--text-primary);
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-lg);
            z-index: 1000;
        }

        .dark-toggle:hover {
            transform: scale(1.1);
            border-color: var(--primary);
            color: var(--primary);
        }

        [data-bs-theme="dark"] .dark-toggle {
            background: var(--card-bg);
        }

        /* Footer */
        .page-footer {
            margin-top: auto;
            padding-top: 2rem;
            text-align: center;
        }

        .footer-text {
            font-size: 0.8125rem;
            color: var(--text-secondary);
        }

        .footer-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .footer-link:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                padding: 1.5rem 1rem;
            }

            .header-title {
                font-size: 1.5rem;
            }

            .header-subtitle {
                font-size: 0.875rem;
            }

            .form-card {
                padding: 1.5rem;
                border-radius: var(--radius-xl);
            }

            .form-group {
                margin-bottom: 1.25rem;
            }

            .btn-submit {
                height: 52px;
            }

            .results-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .results-table {
                font-size: 0.875rem;
            }

            .results-table thead th,
            .results-table tbody td {
                padding: 0.75rem;
            }

            .dark-toggle {
                bottom: 1rem;
                right: 1rem;
                width: 48px;
                height: 48px;
            }
        }

        @media (max-width: 576px) {
            .logo-container {
                width: 70px;
                height: 70px;
            }

            .logo-container img {
                width: 50px;
                height: 50px;
            }

            .form-card {
                padding: 1.25rem;
            }
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-in {
            animation: slideIn 0.4s ease-out;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--border-color);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }
    </style>
</head>
<body>
    <!-- Background Pattern -->
    <div class="bg-pattern"></div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-card">
            <div class="loading-spinner"></div>
            <h5 class="loading-title">Memproses...</h5>
            <p class="loading-text">Mohon tunggu sebentar</p>
        </div>
    </div>

    <div class="main-container">
        <!-- Header -->
        <header class="page-header">
            <div class="header-content">
                <div class="logo-container">
                    <img src="{{ asset('logo-kemenag.png') }}" alt="Logo Kemenag" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <i class="bi bi-file-earmark-pdf text-white" style="display: none; font-size: 2.5rem;"></i>
                </div>
                <div class="header-text">
                    <h1 class="header-title">Slip Gaji ASN</h1>
                    <span class="header-badge">
                         Kementerian Agama Kabupaten Nganjuk
                    </span>
                </div>
            </div>
        </header>

        <!-- Form Card -->
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="form-card">
                    <h2 class="form-title">
                        <span class="form-title-icon">
                            <i class="bi bi-search"></i>
                        </span>
                        Form Pencarian Slip Gaji
                    </h2>

                    <form id="searchForm">
                        <div class="row g-4">
                            <!-- NIP -->
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="nip" class="form-label">
                                        <svg class="form-label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                        Nomor Induk Pegawai (NIP)
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="nip" 
                                           name="nip" 
                                           placeholder="Contoh: 198501232010011001"
                                           maxlength="18"
                                           required>
                                    <div class="invalid-feedback" id="nipError"></div>
                                </div>
                            </div>

                            <!-- Unit Kerja -->
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="unit_kerja" class="form-label">
                                        <svg class="form-label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M3 21h18"/>
                                            <path d="M5 21V7l8-4v18"/>
                                            <path d="M19 21V11l-6-4"/>
                                            <path d="M9 9h1"/>
                                            <path d="M9 13h1"/>
                                            <path d="M9 17h1"/>
                                        </svg>
                                        Unit / Satuan Kerja
                                    </label>
                                    <select class="form-select" id="unit_kerja" name="unit_kerja">
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
                                    <input type="text" 
                                           class="form-control mt-2" 
                                           id="unit_kerja_custom" 
                                           name="unit_kerja_custom"
                                           placeholder="Ketik nama unit kerja..."
                                           style="display: none;">
                                </div>
                            </div>

                            <!-- Periode -->
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="periode" class="form-label">
                                        <svg class="form-label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                            <line x1="16" y1="2" x2="16" y2="6"/>
                                            <line x1="8" y1="2" x2="8" y2="6"/>
                                            <line x1="3" y1="10" x2="21" y2="10"/>
                                        </svg>
                                        Periode
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="periode" 
                                           name="periode" 
                                           placeholder="Pilih Bulan & Tahun"
                                           readonly
                                           required>
                                    <input type="hidden" id="bulan" name="bulan">
                                    <input type="hidden" id="tahun" name="tahun">
                                </div>
                            </div>

                            <!-- Kepentingan -->
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="tujuan_unduh" class="form-label">
                                        <svg class="form-label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                                            <line x1="4" y1="22" x2="4" y2="15"/>
                                        </svg>
                                        Kepentingan / Tujuan
                                    </label>
                                    <select class="form-select" id="tujuan_unduh" name="tujuan_unduh">
                                        <option value="">-- Pilih Keperluan --</option>
                                        @if(isset($keperluan) && is_array($keperluan))
                                            @foreach($keperluan as $item)
                                                @if(is_array($item))
                                                    <option value="{{ $item['nama'] ?? $item['kode'] ?? '' }}">
                                                        {{ $item['nama'] ?? $item['kode'] ?? 'Keperluan' }}
                                                    </option>
                                                @else
                                                    <option value="{{ $item }}">{{ $item }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                    <input type="text" 
                                           class="form-control mt-2" 
                                           id="tujuan_custom" 
                                           name="tujuan_custom"
                                           placeholder="Ketik kepentingan/tujuan..."
                                           style="display: none;">
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-12">
                                <button type="submit" class="btn-submit" id="btnSearch">
                                    <svg class="btn-submit-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="11" cy="11" r="8"/>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                    </svg>
                                    <span id="btnSearchText">Proses Pencarian & Unduh</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Results Section -->
        <div class="row justify-content-center" id="resultsSection" style="display: none;">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="results-card">
                    <div class="results-header">
                        <h5 class="results-title">
                            <i class="bi bi-table"></i>
                            Hasil Pencarian Slip Gaji
                        </h5>
                        <button type="button" class="btn btn-sm btn-light" onclick="printResults()">
                            <i class="bi bi-printer me-1"></i> Cetak
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table results-table mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pegawai</th>
                                    <th>NIP</th>
                                    <th>Unit Kerja</th>
                                    <th>Periode</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="resultsBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="page-footer">
            <p class="footer-text">
                &copy; {{ date('Y') }} <a href="#" class="footer-link"></a> - Kantor Kementerian Agama Kabupaten Nganjuk. All rights reserved.
            </p>
        </footer>
    </div>

    <!-- Download Modal -->
    <div class="modal fade" id="downloadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-download me-2"></i>
                        Download Slip Gaji
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Slip gaji ditemukan. Pilih tujuan pengunduhan:</p>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-modal-download" id="btnDownload" onclick="downloadSlip()">
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
    <button class="dark-toggle" onclick="toggleDarkMode()" title="Toggle Dark Mode">
        <i class="bi bi-{{ $darkMode ? 'sun' : 'moon' }}"></i>
    </button>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    
    <script>
        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2
            $('#unit_kerja').select2({
                placeholder: '-- Pilih Unit Kerja --',
                allowClear: true,
                theme: 'bootstrap4',
                width: '100%'
            });
            
            $('#tujuan_unduh').select2({
                placeholder: '-- Pilih Kepentingan --',
                allowClear: true,
                theme: 'bootstrap4',
                width: '100%'
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
                locale: "id",
                disableMobile: true,
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
            
            // Toggle unit_kerja custom input
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
            
            // Toggle tujuan_unduh custom input
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
            const spinner = document.getElementById('loadingOverlay');
            
            // Get form data
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
                    
                    // Try to get filename from Content-Disposition header
                    const contentDisposition = searchResponse.headers.get('content-disposition');
                    let filename = 'slip_gaji_' + formData.nip + '_' + formData.tahun + ('0' + formData.bulan).slice(-2) + '.pdf';
                    if (contentDisposition) {
                        const filenameMatch = contentDisposition.match(/filename\*?=(?:UTF-8'')?["']?([^"'\s;]+)/i);
                        if (filenameMatch) {
                            filename = decodeURIComponent(filenameMatch[1]);
                        }
                    }
                    
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
                
                // Check if API returned PDF directly in response
                if (searchResult.content_type === 'application/pdf' && searchResult.document) {
                    // API returns PDF as base64 in document field
                    const pdfData = atob(searchResult.document);
                    const pdfBytes = new Uint8Array(pdfData.length);
                    for (let i = 0; i < pdfData.length; i++) {
                        pdfBytes[i] = pdfData.charCodeAt(i);
                    }
                    const pdfBlob = new Blob([pdfBytes], { type: 'application/pdf' });
                    const pdfUrl = window.URL.createObjectURL(pdfBlob);
                    const filename = searchResult.filename || 'slip_gaji_' + formData.nip + '.pdf';
                    
                    const a = document.createElement('a');
                    a.href = pdfUrl;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(pdfUrl);
                    document.body.removeChild(a);
                    
                    showToast('Berhasil!', 'Slip gaji berhasil diunduh', 'success');
                    return;
                }
                
                if (!searchResult.success) {
                    showToast('Gagal', searchResult.message || 'Data slip gaji tidak ditemukan', 'danger');
                    document.getElementById('resultsSection').style.display = 'none';
                    return;
                }
                
                // Get first item for download
                const firstItem = Array.isArray(searchResult.data) ? searchResult.data[0] : searchResult.data;
                if (!firstItem) {
                    showToast('Gagal', 'Data slip gaji tidak ditemukan', 'danger');
                    return;
                }
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
                btnText.innerHTML = '<svg class="btn-submit-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg><span id="btnSearchText">Proses Pencarian & Unduh</span>';
                spinner.classList.remove('show');
            }
        }

        // Display Results
        function displayResults(data) {
            const resultsSection = document.getElementById('resultsSection');
            const tbody = document.getElementById('resultsBody');
            
            if (!data || (Array.isArray(data) && data.length === 0)) {
                resultsSection.style.display = 'none';
                return;
            }
            
            resultsSection.style.display = 'block';
            
            const items = Array.isArray(data) ? data : [data];
            
            tbody.innerHTML = items.map((item, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td><strong>${item.nama || item.name || '-'}</strong></td>
                    <td>${item.nip || '-'}</td>
                    <td>${item.unit_kerja || item.unit || '-'}</td>
                    <td>${item.periode || item.bulan + '/' + item.tahun || '-'}</td>
                    <td>
                        <span class="status-badge">
                            <i class="bi bi-check-circle"></i>
                            Tersedia
                        </span>
                    </td>
                    <td>
                        <button class="btn-download" onclick="openDownloadModal('${item.id || item.slip_id || item.nip}')">
                            <i class="bi bi-download"></i>
                            Download
                        </button>
                    </td>
                </tr>
            `).join('');
            
            // Auto-download first result if available
            if (items.length > 0) {
                const firstItem = items[0];
                const slipId = firstItem.id || firstItem.slip_id || firstItem.nip;
                
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
            
            const header = toast.querySelector('.toast-header');
            if (type === 'success') {
                header.className = 'toast-header bg-success text-white';
                header.querySelector('i').className = 'bi bi-check-circle me-2';
            } else if (type === 'danger') {
                header.className = 'toast-header bg-danger text-white';
                header.querySelector('i').className = 'bi bi-exclamation-circle me-2';
            } else {
                header.className = 'toast-header';
            }
            
            toast.className = `toast bg-${type} text-white`;
            
            const bsToast = new bootstrap.Toast(toast, { delay: 4000 });
            bsToast.show();
        }
    </script>
</body>
</html>
