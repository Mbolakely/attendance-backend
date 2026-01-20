<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Sceance;
use App\Models\student;
use Illuminate\Http\Request;

class AbsenceController extends Controller
{
    public function absences(Request $request)
    {
        $data = $request->validate([
            'date'       => 'nullable|date',
            'from'       => 'nullable|date',
            'to'         => 'nullable|date',
            'student_id' => 'nullable|exists:students,id',
        ]);

        $query = Attendance::with([
            'student',
            'sceance.classe'
        ])->where('status', 'absent');

        /*
    |--------------------------------------
    | Filtre étudiant
    |--------------------------------------
    */
        if (!empty($data['student_id'])) {
            $query->where('student_id', $data['student_id']);
        }

        /*
    |--------------------------------------
    | Filtre date / période
    |--------------------------------------
    */
        if (!empty($data['date'])) {

            //  une seule date
            $query->whereHas('sceance', function ($q) use ($data) {
                $q->whereDate('date', $data['date']);
            });
        } elseif (!empty($data['from']) || !empty($data['to'])) {

            //  intervalle
            $query->whereHas('sceance', function ($q) use ($data) {
                if (!empty($data['from'])) {
                    $q->whereDate('date', '>=', $data['from']);
                }
                if (!empty($data['to'])) {
                    $q->whereDate('date', '<=', $data['to']);
                }
            });
        }

        $absences = $query
            ->orderByDesc(
                Sceance::select('date')
                    ->whereColumn('sceances.id', 'attendances.sceance_id')
            )
            ->get();

        return response()->json([
            'total' => $absences->count(),
            'data'  => $absences,
        ]);
    }

    public function globalKpi()
{
    $totalAttendances = Attendance::count();
    $totalPresent = Attendance::where('status', 'present')->count();
    $totalAbsent = Attendance::where('status', 'absent')->count();

    $totalSceances = Sceance::count();
    $terminatedSceances = Sceance::where('status', 'terminee')->count();

    $todayAbsent = Attendance::where('status', 'absent')
        ->whereHas('sceance', fn ($q) =>
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
