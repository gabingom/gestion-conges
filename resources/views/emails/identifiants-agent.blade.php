<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#f4f5f7;font-family:Arial,Helvetica,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f5f7;padding:28px 12px;">
<tr><td align="center">
<table width="100%" cellpadding="0" cellspacing="0" style="max-width:580px;background:#ffffff;border-radius:10px;overflow:hidden;">

    <tr>
        <td style="background:#1a1a2e;padding:24px 30px;">
            <div style="color:#ffffff;font-size:18px;font-weight:bold;">Gestion des Congés</div>
            <div style="color:#9aa3b2;font-size:11px;letter-spacing:.5px;text-transform:uppercase;margin-top:4px;">
                Université du Sine Saloum El-Hâdj Ibrahima NIASS
            </div>
        </td>
    </tr>

    <tr>
        <td style="padding:30px;">
            <p style="margin:0 0 16px;font-size:15px;color:#1b2233;">Bonjour {{ $nomComplet }},</p>

            <p style="margin:0 0 20px;font-size:14px;color:#555f70;line-height:1.6;">
                Un compte vient d'être créé pour vous sur la plateforme de gestion des congés
                et absences du personnel. Voici vos identifiants de connexion.
            </p>

            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6fa;border-radius:8px;margin:0 0 22px;">
                <tr><td style="padding:18px 20px;">
                    <div style="font-size:11px;color:#8892a4;text-transform:uppercase;letter-spacing:.5px;">Identifiant</div>
                    <div style="font-size:15px;color:#1b2233;font-weight:bold;margin:4px 0 14px;">{{ $identifiant }}</div>

                    <div style="font-size:11px;color:#8892a4;text-transform:uppercase;letter-spacing:.5px;">Mot de passe provisoire</div>
                    <div style="font-size:15px;color:#e94560;font-weight:bold;margin-top:4px;font-family:monospace;">{{ $motDePasse }}</div>
                </td></tr>
            </table>

            <table width="100%" cellpadding="0" cellspacing="0" style="border-left:4px solid #e94560;background:#fdecef;border-radius:6px;margin:0 0 22px;">
                <tr><td style="padding:14px 18px;">
                    <div style="font-size:13px;color:#1b2233;font-weight:bold;margin-bottom:5px;">Important</div>
                    <div style="font-size:13px;color:#555f70;line-height:1.6;">
                        Ce mot de passe est <strong>provisoire</strong>. Vous devrez obligatoirement
                        le remplacer par un mot de passe personnel dès votre première connexion.
                        Ne le communiquez à personne.
                    </div>
                </td></tr>
            </table>

            <table cellpadding="0" cellspacing="0" style="margin:0 0 22px;">
                <tr><td style="background:#1a1a2e;border-radius:26px;">
                    <a href="{{ $lienConnexion }}" style="display:inline-block;padding:12px 30px;color:#ffffff;font-size:14px;font-weight:bold;text-decoration:none;">
                        Accéder à la plateforme
                    </a>
                </td></tr>
            </table>

            <p style="margin:0;font-size:12px;color:#8892a4;line-height:1.6;">
                Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :<br>
                <span style="color:#555f70;">{{ $lienConnexion }}</span>
            </p>
        </td>
    </tr>

    <tr>
        <td style="background:#f4f6fa;padding:16px 30px;">
            <p style="margin:0;font-size:11px;color:#8892a4;line-height:1.6;">
                Message automatique — merci de ne pas y répondre.
                Pour toute question, contactez le service du personnel.
            </p>
        </td>
    </tr>

</table>
</td></tr>
</table>
</body>
</html>
