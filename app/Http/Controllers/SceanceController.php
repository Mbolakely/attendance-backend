<?php

namespace App\Http\Controllers;

use App\Models\Sceance;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class SceanceController extends Controller
{
    // LISTE DES SÉANCES
    public function index()
    {
        try {
            $sceances = Sceance::with('classe')->get();

            return response()->json([
                'success' => true,
                'data' => $sceances
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des séances'
            ], 500);
        }
    }

    // CRÉER UNE SÉANCE
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'classe_id' => 'required|exists:classes,id',
                'date' => 'required|date',
                'debut_sceance' => 'required|date_format:H:i',
                'fin_sceance' => 'required|date_format:H:i|after:debut_sceance',
                'salle' => 'required|string|max:50',
                'matiere' => 'required|string|max:100',
                'professeur' => 'required|string|max:100',
            ]);

            $sceance = Sceance::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Séance créée avec succès',
                'data' => $sceance
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage(),
                'message' => 'Erreur interne lors de la création de la séance'
            ], 500);
        }
    }

    // DÉTAIL D’UNE SÉANCE
    public function show($id)
    {
        try {
            $sceance = Sceance::with('classe')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $sceance
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Séance introuvable'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de la séance'
            ], 500);
        }
    }

    // METTRE À JOUR UNE SÉANCE
    public function update(Request $request, $id)
    {
        try {
            $sceance = Sceance::findOrFail($id);

            $data = $request->validate([
                'classe_id' => 'sometimes|exists:classes,id',
                'date' => 'sometimes|date',
                'debut_sceance' => 'sometimes|date_format:H:i',
                'fin_sceance' => 'sometimes|date_format:H:i|after:debut_sceance',
                'salle' => 'sometimes|string|max:50',
                'matiere' => 'sometimes|string|max:100',
                'professeur' => 'sometimes|string|max:100',
            ]);

            $sceance->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Séance mise à jour',
                'data' => $sceance
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Séance introuvable'
            ], 404);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la séance'
            ], 500);
        }
    }

    // SUPPRIMER UNE SÉANCE
    public function destroy($id)
    {
        try {
            $sceance = Sceance::findOrFail($id);
            $sceance->delete();

            return response()->json([
                'success' => true,
                'message' => 'Séance supprimée'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Séance introuvable'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la séance'
            ], 500);
        }
    }
}
