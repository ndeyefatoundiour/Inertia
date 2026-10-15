<?php

namespace App\Http\Controllers;

use App\Models\Apprenant;
use App\Models\Groupe;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ApprenantController extends Controller
{
    
    public function index()
    {
        return Inertia::render('Apprenants/Index', [
            'apprenants' => Apprenant::with('groupe')->latest()->get(),
            'groupes'    => Groupe::with('apprenants')->orderBy('numero')->get(),
        ]);
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomComplet' => 'required|string|max:255',
            'sexe'       => 'required|in:M,F',
            'niveau'     => 'required|integer|in:1,2,3',
        ]);

        Apprenant::create($validated);

        return redirect()->back();
    }

    
    public function genererGroupes(Request $request)
    {
        $request->validate([
            'taille' => 'required|integer|min:1',
        ]);

        $taille = (int) $request->taille;

        Apprenant::query()->update(['groupe_id' => null]);
        Groupe::query()->delete();

        $apprenants = Apprenant::all();
        $groupes = [];
        $numeroGroupe = 1;

        foreach ([1, 2, 3] as $niveau) {
            $duNiveau = $apprenants->where('niveau', $niveau);

            $garcons = $duNiveau->where('sexe', 'M')->shuffle()->values();
            $filles  = $duNiveau->where('sexe', 'F')->shuffle()->values();

            while ($garcons->count() > 0 || $filles->count() > 0) {
                $membres = collect();

                // Alternance G / F pour favoriser la mixité
                while ($membres->count() < $taille &&
                       ($garcons->count() > 0 || $filles->count() > 0)) {
                    if ($garcons->count() > 0) {
                        $membres->push($garcons->shift());
                    }
                    if ($membres->count() < $taille && $filles->count() > 0) {
                        $membres->push($filles->shift());
                    }
                }

                // Créer le groupe
                $groupeBDD = Groupe::create([
                    'numero' => $numeroGroupe,
                    'niveau' => $niveau,
                ]);

                // Rattacher les apprenants
                Apprenant::whereIn('id', $membres->pluck('id'))
                    ->update(['groupe_id' => $groupeBDD->id]);

                $groupes[] = [
                    'id'      => $groupeBDD->id,
                    'numero'  => $numeroGroupe,
                    'niveau'  => $niveau,
                    'membres' => $membres->values(),
                ];

                $numeroGroupe++;
            }
        }

        return redirect()->back();
    }
}