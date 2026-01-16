<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class ClasseController extends Controller
{
    // LISTE DES CLASSES
    public function index()
    {
        try {
            $classes = Classe::with(['niveau', 'parcours'])->get();

            return response()->json([
                'success' => true,
                'data' => $classes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des classes'
            ], 500);
        }
    }

    // CREER UNE CLASSE
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'libelle' => 'required|unique:classes',
                'code_classe' => 'required|unique:classes',
                'niveau_id' => 'required|exists:niveaux,id',
                'parcours_id' => 'required|exists:parcours,id',
            ]);

            $classe = Classe::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Classe créée avec succès',
                'data' => $classe
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
                'message' => 'Erreur interne lors de la création de la classe'
            ], 500);
        }
    }

    // DETAILS D'UNE CLASSE
    public function show($id)
    {
        try {
            $classe = Classe::with(['niveau', 'parcours'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $classe
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Classe introuvable'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de la classe'
            ], 500);
        }
    }

    // METTRE À JOUR UNE CLASSE
    public function update(Request $request, $id)
    {
        try {
            $classe = Classe::findOrFail($id);

            $data = $request->validate([
                'libelle' => 'sometimes|unique:classes,libelle,' . $id,
                'code_classe' => 'sometimes|unique:classes,code_classe,' . $id,
                'niveau_id' => 'sometimes|exists:niveaux,id',
                'parcours_id' => 'sometimes|exists:parcours,id',
            ]);

            $classe->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Classe mise à jour',
                'data' => $classe
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Classe introuvable'
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
                'message' => 'Erreur lors de la mise à jour de la classe'
            ], 500);
        }
    }

    // SUPPRIMER UNE CLASSE
    public function destroy($id)
    {
        try {
            $classe = Classe::findOrFail($id);
            $classe->delete();

            return response()->json([
                'success' => true,
                'message' => 'Classe supprimée'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Classe introuvable'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la classe'
            ], 500);
        }
    }
}
