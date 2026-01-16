<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // INDEX FUNCTION
    // public function index()
    // {
    //     $students = Student::with('classe')->get();
    //     try {
    //         return response()->json([
    //             'success' => true,
    //             'data' => $students
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Erreur lors du chargement des étudiants'
    //         ], 500);
    //     }
    // }


    public function index(Request $request)
    {
        try {
            $perPage = (int) $request->query('per_page', 10);
            $page = (int) $request->query('page', 1);

            $students = Student::with('classe')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => $students->items(),
                'pagination' => [
                    'current_page' => $students->currentPage(),
                    'last_page' => $students->lastPage(),
                    'per_page' => $students->perPage(),
                    'total' => $students->total(),
                    'from' => $students->firstItem(),
                    'to' => $students->lastItem(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des étudiants'
            ], 500);
        }
    }


    // STORE FUNCTION
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'nom' => 'required',
                'prenom' => 'nullable',
                'email' => 'nullable|email|unique:students',
                'classe_id' => 'required',
                'num_matricule' => 'required|unique:students',
                'cin' => 'required|unique:students',
                'sexe' => 'required|in:Femme,Homme',
                'telephone' => 'required|size:10',
                'adresse' => 'nullable',
                'date_naissance' => 'nullable|date',
                'date_inscription' => 'nullable|date',
            ]);

            $student = Student::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Étudiant créé avec succès',
                'data' => $student
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);

        } 
    }

    // DETAILS FUNCTION
    public function show($id)
    {
        try {
            $student = Student::with('classe')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $student
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Étudiant introuvable'
            ], 404);
        }
    }

    // UPDATE FUNCTION
    public function update(Request $request, $id)
    {
        try {
            $student = Student::findOrFail($id);

            $student->update(
                $request->except('encodage_facial')
            );

            return response()->json([
                'success' => true,
                'message' => 'Étudiant mis à jour',
                'data' => $student
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Étudiant introuvable'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour'
            ], 500);
        }
    }

    // DELETE FUNCTION
    public function destroy($id)
    {
        try {
            $student = Student::findOrFail($id);
            $student->delete();

            return response()->json([
                'success' => true,
                'message' => 'Étudiant supprimé'
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Étudiant introuvable'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression'
            ], 500);
        }
    }
}
