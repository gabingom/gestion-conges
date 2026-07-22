<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changer mon mot de passe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root{ --dark:#1a1a2e; --accent:#e94560; }
        body{margin:0;font-family:'Segoe UI',system-ui,sans-serif;background:#e9ebf0;
             min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;}
        .box{width:100%;max-width:480px;background:#fff;border-radius:16px;padding:38px 40px;
             box-shadow:0 20px 55px rgba(26,26,46,.16);}
        .box h2{font-size:1.5rem;font-weight:800;color:var(--dark);margin:0 0 6px;}
        .box .sub{color:#8892a4;font-size:.9rem;margin:0 0 24px;}
        .form-control{border-radius:10px;border:1px solid #e2e8f0;padding:.65rem .85rem;}
        .form-control:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(233,69,96,.12);}
        label{font-size:.8rem;font-weight:700;color:#4a5568;margin-bottom:5px;display:block;}
        .btn-go{width:100%;border:none;border-radius:26px;padding:.8rem;background:var(--dark);
                color:#fff;font-weight:600;transition:background .2s;}
        .btn-go:hover{background:var(--accent);}
        .icon{width:52px;height:52px;border-radius:14px;background:var(--accent);
              display:flex;align-items:center;justify-content:center;margin-bottom:18px;}
    </style>
</head>
<body>
<div class="box">
    <div class="icon"><i class="bi bi-shield-lock-fill text-white fs-4"></i></div>

    <h2>Changez votre mot de passe</h2>
    <p class="sub">
        Votre mot de passe actuel est provisoire. Pour des raisons de sécurité,
        vous devez le remplacer avant d'accéder à la plateforme.
    </p>

    @if($errors->any())
        <div class="alert alert-danger py-2">
            <ul class="mb-0 ps-3 small">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="current_password">Mot de passe provisoire</label>
            <input type="password" name="current_password" id="current_password" class="form-control"
                   placeholder="Celui reçu par e-mail" required autofocus>
        </div>

        <div class="mb-3">
            <label for="password">Nouveau mot de passe</label>
            <input type="password" name="password" id="password" class="form-control"
                   placeholder="8 caractères minimum" required>
        </div>

        <div class="mb-4">
            <label for="password_confirmation">Confirmer le nouveau mot de passe</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                   placeholder="Retapez le nouveau mot de passe" required>
        </div>

        <button type="submit" class="btn-go">
            <i class="bi bi-check-lg me-1"></i> Valider et continuer
        </button>
    </form>

    <form action="{{ route('logout') }}" method="POST" class="text-center mt-3">
        @csrf
        <button type="submit" class="btn btn-link btn-sm text-muted text-decoration-none">
            Se déconnecter
        </button>
    </form>
</div>
</body>
</html>
