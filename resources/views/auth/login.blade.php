<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Family Gestion de Stock</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary: #059669;
            --primary-hover: #047857;
            --primary-light: #10b981;
            --dark: #0f172a;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #064e3b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Subtle background glow effect */
        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.15) 0%, rgba(0, 0, 0, 0) 70%);
            top: -100px;
            right: -100px;
            border-radius: 50%;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: absolute;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(5, 150, 105, 0.12) 0%, rgba(0, 0, 0, 0) 70%);
            bottom: -100px;
            left: -100px;
            border-radius: 50%;
            pointer-events: none;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            width: 100%;
            max-width: 440px;
            padding: 2.5rem;
            position: relative;
            z-index: 10;
            border: 1px solid rgba(255, 255, 255, 0.4);
            animation: cardFadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes cardFadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .brand-logo-wrap {
            width: 68px;
            height: 68px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            box-shadow: 0 8px 20px rgba(5, 150, 105, 0.25);
            border: 3px solid #fff;
            overflow: hidden;
        }

        .brand-logo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .input-group-text {
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-right: none;
            border-radius: 12px 0 0 12px;
            color: #64748b;
        }

        .form-control {
            border: 1.5px solid #e2e8f0;
            border-left: none;
            border-radius: 0 12px 12px 0;
            padding: 0.75rem 1rem 0.75rem 0.25rem;
            font-size: 0.95rem;
            font-family: inherit;
            color: #0f172a;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: none;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn-submit {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            border: none;
            color: #fff;
            padding: 0.85rem;
            font-weight: 700;
            border-radius: 12px;
            font-size: 1rem;
            box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35);
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
            box-shadow: 0 6px 20px rgba(5, 150, 105, 0.45);
            transform: translateY(-2px);
            color: #fff;
        }

        .password-toggle-btn {
            background-color: transparent;
            border: 1.5px solid #e2e8f0;
            border-left: none;
            border-radius: 0 12px 12px 0;
            color: #64748b;
            cursor: pointer;
            padding: 0 0.85rem;
            display: flex;
            align-items: center;
        }

        .input-group:focus-within .password-toggle-btn {
            border-color: var(--primary);
        }
    </style>
</head>
<body>

    <div class="auth-card">
        <!-- Logo & Header -->
        <div class="text-center mb-4">
            <div class="brand-logo-wrap">
                <img src="{{ asset('images/Fa.jpeg') }}" alt="Logo Family">
            </div>
            <h1 class="h4 fw-bold text-dark mb-1">Family</h1>
            <p class="text-muted small mb-0">Plateforme de Gestion des Stocks</p>
        </div>

        <!-- Success notification -->
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2 py-2 px-3 small rounded-3 mb-3" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('status'))
            <div class="alert alert-success d-flex align-items-center gap-2 py-2 px-3 small rounded-3 mb-3" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <div>{{ session('status') }}</div>
            </div>
        @endif

        <!-- Error notification -->
        @if($errors->any())
            <div class="alert alert-danger d-flex align-items-center gap-2 py-2 px-3 small rounded-3 mb-3" role="alert">
                <i class="bi bi-exclamation-octagon-fill"></i>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('login.submit') }}" autocomplete="off">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary mb-1" for="emailInput">Adresse Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input 
                        type="email" 
                        id="emailInput"
                        name="email" 
                        class="form-control" 
                        placeholder="exemple@family.com" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus>
                </div>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary mb-1" for="passwordInput">Mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                    <input 
                        type="password" 
                        id="passwordInput"
                        name="password" 
                        class="form-control border-end-0" 
                        placeholder="••••••••" 
                        required>
                    <button type="button" class="password-toggle-btn" id="togglePasswordBtn" title="Afficher/Masquer le mot de passe">
                        <i class="bi bi-eye text-muted" id="togglePasswordIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Remember & Forgot -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                    <label class="form-check-label small text-muted" for="rememberMe">
                        Se souvenir de moi
                    </label>
                </div>

                <a href="{{ route('password.request') }}" class="text-decoration-none small fw-semibold text-success">
                    Mot de passe oublié ?
                </a>
            </div>

            <!-- Submit button -->
            <button type="submit" class="w-100 btn btn-submit d-flex align-items-center justify-content-center gap-2">
                <span>Se connecter</span>
                <i class="bi bi-arrow-right"></i>
            </button>
        </form>

        <div class="text-center mt-4 pt-2 border-top">
            <small class="text-muted" style="font-size: 0.75rem;">
                &copy; {{ date('Y') }} Family. Tous droits réservés.
            </small>
        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('passwordInput');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        if (toggleBtn && passwordInput && toggleIcon) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                toggleIcon.classList.toggle('bi-eye', !isPassword);
                toggleIcon.classList.toggle('bi-eye-slash', isPassword);
            });
        }
    </script>
</body>
</html>