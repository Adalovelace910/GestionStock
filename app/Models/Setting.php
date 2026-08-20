<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

    protected $table = 'parametres';


    protected $fillable = [

        'nom_entreprise',
        'adresse',
        'telephone',
        'seuil_stock_bas',
        'emails_alertes',
        'unite_mesure_defaut',
        'elements_par_page',
        'poids_sac_kg',

    ];

    /**
     * Retourne la liste des emails d'alerte sous forme de tableau propre
     * (un email par ligne dans le champ, on filtre les lignes vides).
     */
    public function listeEmailsAlertes(): array
    {
        if (empty($this->emails_alertes)) {
            return [];
        }

        return collect(preg_split('/[\r\n,]+/', $this->emails_alertes))
            ->map(fn ($email) => trim($email))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

}