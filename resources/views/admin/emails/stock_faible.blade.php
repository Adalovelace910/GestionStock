<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Alerte stock faible</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color:#f4f4f4; padding: 20px;">

    <div style="max-width: 500px; margin: 0 auto; background:#ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">

        <div style="background-color:#198754; color:#ffffff; padding: 16px 24px;">
            <h2 style="margin:0;">⚠ Alerte stock faible</h2>
        </div>

        <div style="padding: 24px;">

            <p>Bonjour,</p>

            <p>Le produit suivant vient d'atteindre le seuil minimum de stock défini dans les paramètres de GestionStock :</p>

            <table style="width:100%; border-collapse: collapse; margin: 16px 0;">
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight:bold;">Produit</td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $produit->nom }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight:bold;">Quantité restante</td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; color:#dc3545; font-weight:bold;">{{ $produit->quantite }}</td>
                </tr>
            </table>

            <p>Merci de réapprovisionner ce produit dès que possible.</p>

            <p style="color:#888; font-size: 13px; margin-top: 24px;">
                Cet email a été envoyé automatiquement par GestionStock.
            </p>

        </div>

    </div>

</body>
</html>