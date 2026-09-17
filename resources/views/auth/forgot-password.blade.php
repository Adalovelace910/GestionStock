<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - Family</title>

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
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .brand-icon-wrap {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: #ecfdf5;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin: 0 auto 1.25rem;
            border: 1px solid #a7f3d0;
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
    </style>
</head>
<body>

    <div class="auth-card">
        <!-- Header -->
        <div class="text-center mb-4">
            <div class="brand-icon-wrap">
                <i class="bi bi-key-fill"></i>
            </div>
            <h1 class="h4 fw-bold text-dark mb-1">Mot de passe oublié ?</h1>
            <p class="text-muted small mb-0">
                Saisissez votre adresse email et nous vous enverrons un lien de réinitialisation sécurisé.
            </p>
        </div>

        @if(session('status'))
            <div class="alert alert-success d-flex align-items-center gap-2 py-2 px-3 small rounded-3 mb-4" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <div>{{ session('status') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger d-flex align-items-center gap-2 py-2 px-3 small rounded-3 mb-4" role="alert">
                <i class="bi bi-exclamation-octagon-fill"></i>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-4">
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

            <button type="submit" class="w-100 btn btn-submit d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-send-fill"></i>
                <span>Envoyer le lien de réinitialisation</span>
            </button>
        </form>

        <div class="text-center mt-4 pt-3 border-top">
            <a href="{{ route('login') }}" class="text-decoration-none small fw-semibold text-success d-inline-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i>
                <span>Retour à la page de connexion</span>
            </a>
        </div>
    </div>

</body>
</html>