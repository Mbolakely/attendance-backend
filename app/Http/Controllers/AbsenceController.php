<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Sceance;
use App\Models\student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AbsenceController extends Controller
{
// Listing
    public function absences(Request $request)
    {
        try {
            $perPage = (int) $request->query('per_page', 10);
            $page = (int) $request->query('page', 1);

            $data = $request->validate([
                'date'       => 'nullable|date',
                'from'       => 'nullable|date',
                'to'         => 'nullable|date',
                'student_id' => 'nullable|exists:students,id',
                'classe_id'  => 'nullable|exists:classes,id',
            ]);

            $query = Attendance::with(['student', 'sceance.classe'])
                ->where('status', 'absent');

            // Filtre Étudiant
            if (!empty($data['student_id'])) {
                $query->where('student_id', $data['student_id']);
            }

            // Filtre Classe
            if (!empty($data['classe_id'])) {
                $query->whereHas('sceance', function ($q) use ($data) {
                    $q->where('classe_id', $data['classe_id']);
                });
            }

            // Filtre Dates
            if (!empty($data['date']) || !empty($data['from']) || !empty($data['to'])) {
                $query->whereHas('sceance', function ($q) use ($data) {
                    if (!empty($data['date'])) {
                        $q->whereDate('date', $data['date']);
                    } else {
                        if (!empty($data['from'])) {
                            $q->whereDate('date', '>=', $data['from']);
                        }
                        if (!empty($data['to'])) {
                            $q->whereDate('date', '<=', $data['to']);
                        }
                    }
                });
            }

            // Exécution de la pagination
            $absences = $query
                ->orderByDesc(
                    Sceance::select('date')
                        ->whereColumn('sceances.id', 'attendances.sceance_id')
                        ->limit(1)
                )
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => $absences->items(),
                'pagination' => [
                    'current_page' => $absences->currentPage(),
                    'last_page'    => $absences->lastPage(),
                    'per_page'     => $absences->perPage(),
                    'total'        => $absences->total(),
                    'from'         => $absences->firstItem(),
                    'to'           => $absences->lastItem(),
                ],

            ]);
        } catch (\Exception $e) {
            Log::error('Erreur Absences:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des absences',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    //Update pour les notes et presences manuelles
    public function updateManual(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'notes' => 'nullable|string|max:255'
            ]);

            $attendance = Attendance::findOrFail($id);

            if ($attendance->status === 'present') {
                return response()->json([
                    'success' => false,
                    'message' => 'Action impossible : cet étudiant est déjà marqué présent pour cette séance.'
                ], 422);
            }

            $messageAuto = "Présence effectuée manuellement (Hors reconnaissance faciale) le " . now()->format('d/m/Y H:i');

            $nouvelleNote = !empty($data['notes'])
                ? $data['notes']
                : $messageAuto;

            $noteFinale = $attendance->notes
                ? $attendance->notes . " | " . $nouvelleNote
                : $nouvelleNote;

            $attendance->update([
                'status' => 'present',
                'confiance' => 1.0,
                'notes' => $noteFinale
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Le statut a été mis à jour avec succès.',
                'data' => $attendance->load('student')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la modification.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function globalKpi()
    {
        $totalAttendances = Attendance::count();
        $totalPresent = Attendance::where('status', 'present')->count();
        $totalAbsent = Attendance::where('status', 'absent')->count();

        $totalSceances = Sceance::count();
        $terminatedSceances = Sceance::where('status', 'terminee')->count();

        $todayAbsent = Attendance::where('status', 'absent')
            ->whereHas(
                'sceance',
                fn($q) =>
                $q->whereDate('date', now())
            )->count();

        $topAbsents = Attendance::selectRaw('student_id, COUNT(*) as total')
            ->where('status', 'absent')
            ->groupBy('student_id')
            ->orderByDesc('total')
            ->with('student')
            ->limit(5)
            ->get();

        return response()->json([
            'sceances' => [
                'total' => $totalSceances,
                'terminees' => $terminatedSceances,
            ],
            'attendance' => [
                'total' => $totalAttendances,
                'present' => $totalPresent,
                'absent' => $totalAbsent,
                'taux_presence' => $totalAttendances
                    ? round(($totalPresent / $totalAttendances) * 100, 2)
                    : 0,
                'taux_absence' => $totalAttendances
                    ? round(($totalAbsent / $totalAttendances) * 100, 2)
                    : 0,
            ],
            'today_absent' => $todayAbsent,
            'top_absents' => $topAbsents,
        ]);
    }

    //KPI
    public function studentKpi(Request $request, student $student)
    {
        $request->validate([
            'from' => 'nullable|date',
            'to'   => 'nullable|date',
        ]);

        $query = Attendance::where('student_id', $student->id);

        if ($request->filled('from') || $request->filled('to')) {
            $query->whereHas('sceance', function ($q) use ($request) {
                if ($request->filled('from')) {
                    $q->whereDate('date', '>=', $request->from);
                }
                if ($request->filled('to')) {
                    $q->whereDate('date', '<=', $request->to);
                }
            });
        }

        $total = $query->count();
        $present = (clone $query)->where('status', 'present')->count();
        $absent = (clone $query)->where('status', 'absent')->count();

        $lastAbsence = Attendance::where('student_id', $student->id)
            ->where('status', 'absent')
            ->latest('created_at')
            ->first();

        return response()->json([
            'student' => [
                'id' => $student->id,
                'nom' => $student->nom,
                'prenom' => $student->prenom,
            ],
            'stats' => [
                'total_sceances' => $total,
                'present' => $present,
                'absent' => $absent,
                'taux_presence' => $total
                    ? round(($present / $total) * 100, 2)
                    : 0,
                'taux_absence' => $total
                    ? round(($absent / $total) * 100, 2)
                    : 0,
                'last_absence' => $lastAbsence?->sceance?->date,
            ],
        ]);
    }
}
