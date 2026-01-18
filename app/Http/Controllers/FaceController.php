<?php

namespace App\Http\Controllers;

use App\Models\student;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FaceController extends Controller
{
    public function encode(Request $request, $matricule)
    {
        try {
            $request->validate([
                'encodage_facial' => 'required|array'
            ]);

            $student = student::where('num_matricule', $matricule)->firstOrFail();

            if (!empty($student->encodage_facial)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Encodage facial déjà enregistré'
                ], 409);
            }

            $student->encodage_facial = $request->encodage_facial;
            $student->save();

            return response()->json([
                'success' => true,
                'message' => 'Encodage facial enregistré avec succès'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Encodage invalide',
                'errors' => $e->errors()
            ], 422);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Étudiant introuvable'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l’enregistrement facial'
            ], 500);
        }
    }
}
