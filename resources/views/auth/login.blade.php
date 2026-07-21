<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Gestion des Congés USSEIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root{
            --dark:#1a1a2e;
            --dark-2:#16213e;
            --accent:#e94560;
            --accent-h:#c73652;
        }
        *{box-sizing:border-box;}
        body{
            margin:0;
            font-family:'Segoe UI',system-ui,sans-serif;
            background:#fff;
        }

        /* ── CONTENEUR SPLIT ── */
        .auth-wrapper{
            width:100vw;
            min-height:100vh;
            background:#fff;
            display:grid;
            grid-template-columns:1fr 1fr;
        }

        /* ── PANNEAU GAUCHE (image / dégradé) ── */
        .auth-visual{
            position:relative;
            padding:48px 56px;
            color:#fff;
            display:flex;
            flex-direction:column;
            justify-content:space-between;
            background:
                linear-gradient(150deg, rgba(26,26,46,.86), rgba(22,33,62,.92)),
                radial-gradient(circle at 75% 15%, rgba(233,69,96,.45), transparent 45%),
                radial-gradient(circle at 10% 90%, rgba(0,137,123,.35), transparent 45%),
                var(--dark-2);
        }
        .auth-visual::after{
            content:"";
            position:absolute;inset:0;
            background-image:
                linear-gradient(rgba(255,255,255,.05) 1px,transparent 1px),
                linear-gradient(90deg,rgba(255,255,255,.05) 1px,transparent 1px);
            background-size:42px 42px;
            opacity:.5;
            pointer-events:none;
        }
        .auth-visual .brand,
        .auth-visual .visual-text{position:relative;z-index:1;}

        .auth-visual .brand{
            display:flex;align-items:center;gap:12px;
            font-weight:700;font-size:1.15rem;letter-spacing:.3px;
        }
        .auth-visual .brand .brand-icon{
            width:40px;height:40px;border-radius:11px;
            background:var(--accent);
            display:flex;align-items:center;justify-content:center;
            font-size:1.25rem;
        }
        .auth-visual .brand small{
            display:block;font-size:.62rem;font-weight:400;
            color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:.6px;
        }

        .auth-visual .visual-text h1{
            font-size:2.1rem;font-weight:800;line-height:1.18;margin:0 0 14px;
        }
        .auth-visual .visual-text p{
            color:rgba(255,255,255,.72);font-size:.92rem;max-width:330px;margin:0;
        }
        .auth-visual .dots{display:flex;gap:7px;margin-top:22px;}
        .auth-visual .dots span{height:4px;border-radius:4px;background:rgba(255,255,255,.3);width:18px;}
        .auth-visual .dots span.active{background:var(--accent);width:34px;}

        /* ── PANNEAU DROIT (formulaire) ── */
        .auth-form{
            padding:54px 56px;
            display:flex;flex-direction:column;justify-content:center;
        }
        .auth-form h2{font-size:1.9rem;font-weight:800;color:var(--dark);margin:0 0 6px;}
        .auth-form .subtitle{color:#8892a4;font-size:.92rem;margin:0 0 30px;}

        .auth-form label{
            font-size:.8rem;font-weight:700;color:#4a5568;margin-bottom:6px;display:block;
        }
        .auth-form .form-control{
            border-radius:11px;border:1px solid #e2e8f0;padding:.7rem .9rem;font-size:.92rem;
        }
        .auth-form .form-control:focus{
            border-color:var(--accent);box-shadow:0 0 0 3px rgba(233,69,96,.12);
        }
        .pwd-wrap{position:relative;}
        .pwd-wrap .toggle-eye{
            position:absolute;right:14px;top:50%;transform:translateY(-50%);
            border:none;background:none;color:#9aa3b2;cursor:pointer;font-size:1.05rem;
        }

        .check-row{display:flex;align-items:center;justify-content:space-between;margin:16px 0 26px;}
        .check-row .form-check-label{font-size:.85rem;color:#5a6478;}

        .btn-login{
            width:100%;border:none;border-radius:30px;padding:.85rem;
            background:var(--dark);color:#fff;font-weight:600;font-size:.95rem;
            transition:background .2s;cursor:pointer;
        }
        .btn-login:hover{background:var(--accent);}

        .alert{border-radius:11px;border:none;font-size:.86rem;}

        /* ── RESPONSIVE ── */
        @media (max-width:820px){
            .auth-wrapper{grid-template-columns:1fr;min-height:100vh;}
            .auth-visual{display:none;}
            .auth-form{padding:40px 30px;}
        }
    </style>
</head>
<body>

<div class="auth-wrapper">

    <!-- GAUCHE : visuel + slogan -->
    <div class="auth-visual">
        <div class="brand">
            <div class="brand-icon"><i class="bi bi-calendar2-check"></i></div>
            <span>
                Gestion des Congés
                <small>USSEIN</small>
            </span>
        </div>
        <div class="visual-text">
            <h1>Vos congés.<br>Gérés simplement.</h1>
            <p>Plateforme de gestion des congés et absences du personnel de l'Université du Sine Saloum El-Hâdj Ibrahima NIASS.</p>
            <div class="dots">
                <span class="active"></span><span></span><span></span>
            </div>
        </div>
    </div>

    <!-- DROITE : formulaire (inchangé fonctionnellement) -->
    <div class="auth-form">
        <h2>Bon retour !</h2>
        <p class="subtitle">Connectez-vous pour accéder à votre espace.</p>

        @if($errors->any())
            <div class="alert alert-danger py-2 mb-3">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="email">Adresse e-mail</label>
                <input type="email" name="email" id="email" class="form-control"
                       value="{{ old('email') }}" placeholder="Saisissez votre e-mail"
                       autocomplete="off" required autofocus>
            </div>

            <div class="mb-1">
                <label for="password">Mot de passe</label>
                <div class="pwd-wrap">
                    <input type="password" name="password" id="password" class="form-control"
                           placeholder="Saisissez votre mot de passe" required>
                    <button type="button" class="toggle-eye" onclick="togglePwd()" aria-label="Afficher le mot de passe">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="check-row">
                <div class="form-check m-0">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember"
                           {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">Se souvenir de moi</label>
                </div>
            </div>

            <button type="submit" class="btn-login">
                Se connecter
            </button>
        </form>
    </div>

</div>

<script>
    function togglePwd(){
        var input = document.getElementById('password');
        var icon  = document.getElementById('eyeIcon');
        if (input.type === 'password'){ input.type = 'text';  icon.className = 'bi bi-eye-slash'; }
        else                          { input.type = 'password'; icon.className = 'bi bi-eye'; }
    }
</script>

</body>
</html>