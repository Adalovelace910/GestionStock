<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\MatierePremiere;
use App\Models\Notification;
use App\Models\Production;
use App\Models\RegleProduction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MatierePremiereController extends Controller
{
    public function index()
    {
        $matieresPremieres =
            MatierePremiere::orderBy('nom')
                ->paginate(10);

        return view(
            'admin.matierespremieres.index',
            compact('matieresPremieres')
        );
    }

    public function create()
    {
        return view(
            'admin.matierespremieres.create'
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'nom' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-zÀ-ÿ][A-Za-zÀ-ÿ0-9\s\'\-]*$/',
                Rule::unique(
                    'matieres_premieres',
                    'nom'
                ),
            ],

            'description' =>
                ['nullable', 'string', 'max:1000'],

            'quantite' =>
                ['required', 'integer', 'min:0'],

            'date_ajout' =>
                ['required', 'date'],

            'prix' =>
                ['required', 'numeric', 'min:0'],
        ]);


        $matierePremiere =
            MatierePremiere::create(
                $validated
            );


        Notification::create([

            'title' =>
                'Nouvelle matière première',

            'message' =>
                "La matière première "
                . "\"{$matierePremiere->nom}\" "
                . "a été ajoutée.",

            'icon' =>
                'bi-box-seam',
        ]);


        ActivityLog::log(
            'operation',
            "A créé la matière première "
            . "\"{$matierePremiere->nom}\""
        );


        return redirect()
            ->route(
                'admin.matieres-premieres.index'
            )
            ->with(
                'success',
                'Matière première ajoutée avec succès.'
            );
    }

    public function edit(
        MatierePremiere $matieresPremiere
    ) {
        return view(
            'admin.matierespremieres.edit',
            [
                'matierePremiere' =>
                    $matieresPremiere,
            ]
        );
    }

    public function update(
        Request $request,
        MatierePremiere $matieresPremiere
    ) {
        $validated = $request->validate([

            'nom' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-zÀ-ÿ][A-Za-zÀ-ÿ0-9\s\'\-]*$/',
                Rule::unique(
                    'matieres_premieres',
                    'nom'
                )->ignore(
                    $matieresPremiere->id
                ),
            ],

            'description' =>
                ['nullable', 'string', 'max:1000'],

            'quantite' =>
                ['required', 'integer', 'min:0'],

            'date_ajout' =>
                ['required', 'date'],

            'prix' =>
                ['required', 'numeric', 'min:0'],
        ]);


        $matieresPremiere->update(
            $validated
        );


        ActivityLog::log(
            'operation',
            "A modifié la matière première "
            . "\"{$matieresPremiere->nom}\""
        );


        return redirect()
            ->route(
                'admin.matieres-premieres.index'
            )
            ->with(
                'success',
                'Matière première modifiée avec succès.'
            );
    }

    public function destroy(
        MatierePremiere $matieresPremiere
    ) {
        /*
         * On ne supprime pas une matière première
         * qui possède déjà un historique de production.
         */
        if (
            Production::where(
                'matiere_premiere_id',
                $matieresPremiere->id
            )->exists()
        ) {

            return back()->with(
                'error',
                'Cette matière première ne peut pas être supprimée car elle possède déjà un historique de production.'
            );
        }


        $nom =
            $matieresPremiere->nom;


        RegleProduction::where(
            'matiere_premiere_id',
            $matieresPremiere->id
        )->delete();


        $matieresPremiere->delete();


        ActivityLog::log(
            'operation',
            "A supprimé la matière première "
            . "\"{$nom}\""
        );


        return redirect()
            ->route(
                'admin.matieres-premieres.index'
            )
            ->with(
                'success',
                'Matière première supprimée avec succès.'
            );
    }
}