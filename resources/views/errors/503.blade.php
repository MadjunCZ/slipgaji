<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/png" href="{{ asset('logo-kemenag.png') }}">
    <title>Maintenance - Slip Gaji ASN</title>
    
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
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
            --shadow-primary: 0 4px 14px 0 rgba(5, 150, 105, 0.35);
            --radius-xl: 20px;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg-gradient-start: #022c22;
                --bg-gradient-end: #064e3b;
                --card-bg: rgba(6, 78, 59, 0.5);
                --text-primary: #ecfdf5;
                --text-secondary: #9ca3af;
            }
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
            display: flex;
            align-items: center;
            justify-content: center;
        }

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
            align-items: center;
            justify-content: center;
        }

        .error-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: var(--radius-xl);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            max-width: 500px;
            width: 100%;
            text-align: center;
            overflow: hidden;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .icon-wrapper {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            padding: 3rem 2rem;
            position: relative;
        }

        .icon-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.3;
        }

        .maintenance-icon {
            font-size: 5rem;
            color: white;
            position: relative;
            z-index: 1;
            animation: wiggle 2s ease-in-out infinite;
        }

        @keyframes wiggle {
            0%, 100% { transform: rotate(-5deg); }
            50% { transform: rotate(5deg); }
        }

        .content {
            padding: 2.5rem 2rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1.25rem;
        }

        h1 {
            color: var(--text-primary);
            font-size: 1.75rem;
            margin-bottom: 0.75rem;
            font-weight: 700;
        }

        .lead {
            color: var(--text-secondary);
            font-size: 1rem;
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }

        .info-box {
            background: rgba(5, 150, 105, 0.08);
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--primary);
            text-align: left;
        }

        .info-box p {
            margin: 0;
            font-size: 0.875rem;
            color: var(--text-primary);
        }

        .info-box .icon {
            color: var(--primary);
            margin-right: 0.5rem;
        }

        .refresh-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 0.75rem 1.75rem;
            border: none;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
        }

        .refresh-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-primary);
            color: white;
        }

        .spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .footer {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(5, 150, 105, 0.1);
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        .loading-dot {
            display: inline-block;
            animation: loading 1.4s infinite both;
        }

        .loading-dot:nth-child(2) { animation-delay: 0.2s; }
        .loading-dot:nth-child(3) { animation-delay: 0.4s; }

        @keyframes loading {
            0%, 80%, 100% { 
                transform: scale(0);
                opacity: 0.5;
            }
            40% { 
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Dark mode */
        @media (prefers-color-scheme: dark) {
            .info-box {
                background: rgba(16, 185, 129, 0.15);
            }

            .footer {
                border-top-color: rgba(16, 185, 129, 0.2);
            }
        }

        /* Mobile responsive */
        @media (max-width: 576px) {
            .main-container {
                padding: 1rem;
            }

            .icon-wrapper {
                padding: 2rem 1.5rem;
            }

            .maintenance-icon {
                font-size: 4rem;
            }

            .content {
                padding: 2rem 1.5rem;
            }

            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="bg-pattern"></div>
    
    <div class="main-container">
        <div class="error-card">
            <div class="icon-wrapper">
                <div class="maintenance-icon">🔧</div>
            </div>
            
            <div class="content">
                <span class="status-badge">
                    <i class="bi bi-tools"></i>
                    Maintenance Mode
                </span>
                
                <h1>Sedang Dalam Perbaikan</h1>
                
                <p class="lead">
                    Kami sedang melakukan maintenance sistem untuk meningkatkan kualitas layanan.
                    Mohon maaf atas ketidaknyamanannya.
                </p>

                <div class="info-box">
                    <p>
                        <span class="icon">📋</span>
                        Halaman akan otomatis refresh setelah maintenance selesai
                    </p>
                </div>

                <button class="refresh-btn" onclick="checkMaintenance()">
                    <i class="bi bi-arrow-clockwise spinner"></i>
                    <span id="btn-text">Cek Status</span>
                </button>

                <div class="footer">
                    <p><i class="bi bi-shield-lock"></i> Slip Gaji ASN</p>
                    <p style="margin-top: 0.5rem;">
                        Halaman akan otomatis refresh<span class="loading-dot">.</span><span class="loading-dot">.</span><span class="loading-dot">.</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Auto refresh setiap 30 detik
        let refreshInterval = 30000;
        
        function checkMaintenance() {
            const btn = document.getElementById('btn-text');
            const btnIcon = document.querySelector('.refresh-btn .spinner');
            
            btn.textContent = 'Memeriksa...';
            btnIcon.style.display = 'inline-block';

            fetch(window.location.href)
                .then(response => {
                    if (response.status === 200) {
                        window.location.reload();
                    } else if (response.status === 503) {
                        btn.textContent = 'Cek Status';
                        btnIcon.style.display = 'none';
                    }
                })
                .catch(() => {
                    btn.textContent = 'Cek Status';
                    btnIcon.style.display = 'none';
                });
        }

        // Auto refresh halaman secara periodik
        setInterval(() => {
            fetch(window.location.href)
                .then(response => {
                    if (response.status === 200) {
                        window.location.reload();
                    }
                })
                .catch(() => {
                    // Silent fail, akan coba lagi
                });
        }, refreshInterval);
    </script>
</body>
</html>
