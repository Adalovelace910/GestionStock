<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\In;
use App\Models\MatierePremiere;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ProductionService
{
    /**
     * Poids fixe d'un sac (en kg), configuré dans Paramètres > Gestion du stock.
     * Utilisé pour toutes les conversions kg <-> sac du module Production.
     */
    public static function poidsSacKg(): float
    {
        $parametre = Setting::first();

        $poids = (float) ($parametre->poids_sac_kg ?? 50);

        return $poids > 0 ? $poids : 50;
    }

    /**
     * Convertit une quantité saisie (en kg ou en sac) vers des kg.
     */
    public static function versKg(float $quantite, string $unite): float
    {
        return $unite === 'sac'
            ? round($quantite * self::poidsSacKg(), 2)
            : round($quantite, 2);
    }

    /**
     * Calcule, à titre d'aperçu, le nombre de sacs de produit fini qu'on
     * obtiendrait avec une quantité de matière première (en kg) et un
     * rendement (%) donnés. Ne touche à aucune donnée en base.
     */
    public static function estimerSacsProduits(float $quantiteMpKg, ?float $rendement): int
    {
        if ($rendement === null || $quantiteMpKg <= 0) {
            return 0;
        }

        $kgProduits = $quantiteMpKg * ($rendement / 100);

        return (int) round($kgProduits / self::poidsSacKg());
    }

    /**
     * Enregistre une production : consomme la matière première (en kg),
     * calcule automatiquement la quantité de produit fini obtenue (en
     * sacs) grâce au rendement fixe défini sur la matière première, met
     * à jour les deux stocks dans une transaction, journalise l'opération
     * et notifie les administrateurs.
     *
     * @throws RuntimeException si la matière première n'est liée à aucun
     *                          produit, si son rendement n'est pas défini,
     *                          si le stock est insuffisant, ou si la
     *                          production obtenue serait de 0 sac.
     */
    public static function produire(
        MatierePremiere $matierePremiere,
        float $quantiteMpKg,
        string $dateEntree,
        User $auteur
    ): In {
        $produit = $matierePremiere->produit;

        if (! $produit) {
            throw new RuntimeException(
                "\"{$matierePremiere->nom}\" n'est liée à aucun produit. Associez-la à un produit depuis la page Produits avant de produire."
            );
        }

        if ($matierePremiere->rendement === null) {
            throw new RuntimeException(
                "Le rendement de \"{$matierePremiere->nom}\" n'est pas défini. Renseignez-le en modifiant la matière première."
            );
        }

        if ($quantiteMpKg <= 0) {
            throw new RuntimeException('La quantité utilisée doit être supérieure à 0.');
        }

        if ($quantiteMpKg > $matierePremiere->quantite) {
            throw new RuntimeException(
                "Stock insuffisant : il ne reste que {$matierePremiere->quantite} kg de \"{$matierePremiere->nom}\" en stock."
            );
        }

        $sacsProduits = self::estimerSacsProduits($quantiteMpKg, (float) $matierePremiere->rendement);

        if ($sacsProduits < 1) {
            throw new RuntimeException(
                'Avec cette quantité, la production obtenue serait de 0 sac. Augmentez la quantité de matière première utilisée.'
            );
        }

        $rendement = $matierePremiere->rendement;

        $entree = DB::transaction(function () use ($matierePremiere, $produit, $quantiteMpKg, $sacsProduits, $dateEntree, $auteur, $rendement) {

            $matierePremiere->decrement('quantite', $quantiteMpKg);

            $ancienneQuantiteProduit = $produit->quantite;

            $produit->increment('quantite', $sacsProduits);

            $entree = In::create([
                'produit_id' => $produit->id,
                'matiere_premiere_id' => $matierePremiere->id,
                'quantite_matiere_premiere' => $quantiteMpKg,
                'rendement' => $rendement,
                'quantite' => $sacsProduits,
                'date_entree' => $dateEntree,
                'user_id' => $auteur->id,
            ]);

            $produit->refresh();
            $produit->verifierSeuilStock($ancienneQuantiteProduit);

            return $entree;
        });

        Notification::create([
            'title' => 'Production enregistrée',
            'message' => "{$auteur->name} a produit {$sacsProduits} sac(s) de \"{$produit->nom}\" à partir de {$quantiteMpKg} kg de \"{$matierePremiere->nom}\" (rendement {$rendement}%).",
            'icon' => 'bi-gear-wide-connected',
        ]);

        ActivityLog::log(
            'operation',
            "A enregistré une production de {$sacsProduits} sac(s) de \"{$produit->nom}\" à partir de {$quantiteMpKg} kg de \"{$matierePremiere->nom}\" (rendement {$rendement}%)"
        );

        return $entree;
    }
}