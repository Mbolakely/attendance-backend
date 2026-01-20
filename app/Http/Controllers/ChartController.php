<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class ChartController extends Controller
{
      public function index(Request $request)
    {
        $from = $request->query('from');
        $to   = $request->query('to');

        /* =======================
           1. Présent vs Absent
        ======================= */
        $presencePie = Attendance::selectRaw('status, COUNT(*) as total')
            ->when($from || $to, function ($q) use ($from, $to) {
                $q->whereHas('sceance', function ($q2) use ($from, $to) {
                    if ($from) $q2->whereDate('date', '>=', $from);
                    if ($to)   $q2->whereDate('date', '<=', $to);
                });
            })
            ->groupBy('status')
            ->get();

        /* =======================
           2. Absences par date
        ======================= */
        $absenceByDate = Attendance::where('attendances.status', 'absent')
            ->selectRaw('DATE(sceances.date) as date, COUNT(*) as total')
            ->join('sceances', 'sceances.id', '=', 'attendances.sceance_id')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        /* =======================
           3. Taux par classe
        ======================= */
        $byClasse = Attendance
        ::selectRaw('classes.libelle as classe, attendances.status, COUNT(*) as total')
            ->join('sceances', 'sceances.id', '=', 'attendances.sceance_id')
            ->join('classes', 'classes.id', '=', 'sceances.classe_id')
            ->groupBy('classes.libelle', 'attendances.status')
            ->get()
            ->groupBy('classe')
            ->map(function ($rows) {
                $total = $rows->sum('total');
                $present = $rows->where('status', 'present')->sum('total');

                return [
                    'taux_presence' => $total
                        ? round(($present / $total) * 100, 2)
                        : 0
                ];
            });

        /* =======================
           4. Top absents
        ======================= */
        $topAbsents = Attendance::selectRaw('students.nom, students.prenom, COUNT(*) as total')
            ->where('status', 'absent')
            ->join('students', 'students.id', '=', 'attendances.student_id')
            ->groupBy('students.nom', 'students.prenom')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return response()->json([
            'presencePie' => $presencePie,
            'absenceByDate' => $absenceByDate,
            'byClasse' => $byClasse,
            'topAbsents' => $topAbsents,
        ]);
    }
}
