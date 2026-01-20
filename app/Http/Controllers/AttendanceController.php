<?php

namespace App\Http\Controllers;

use App\Jobs\SendAttendanceEmailsJob;
use App\Models\Attendance;
use App\Models\Sceance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function attend(Request $request)
    {
        $data = $request->validate([
            'sceance_id' => 'required|exists:sceances,id',
            'students' => 'required|array',
            'students.*.id' => 'required|exists:students,id',
            'students.*.confiance' => 'nullable|numeric|min:0|max:1',
            'students.*.present' => 'required|boolean',
        ]);

        $sceance = Sceance::with('classe.students')->findOrFail($data['sceance_id']);

        if ($sceance->status !== 'en_cours') {
            return response()->json([
                'message' => 'La séance n’est pas active'
            ], 403);
        }

        // map [student_id => data]
        $studentsMap = collect($data['students'])->keyBy(function ($item) {
            return (int) $item['id'];
        });

        DB::transaction(function () use ($sceance, $data) {
            // 1. On récupère les IDs des étudiants de cette classe pour sécurité
            $allowedStudentIds = $sceance->classe->students->pluck('id')->toArray();

            foreach ($data['students'] as $studentInput) {
                $studentId = (int) $studentInput['id'];

                // Sécurité : on ne pointe que les élèves qui appartiennent à la classe de la séance
                if (!in_array($studentId, $allowedStudentIds)) {
                    continue;
                }

                $isPresent = filter_var($studentInput['present'], FILTER_VALIDATE_BOOLEAN);

                Attendance::updateOrCreate(
                    [
                        'sceance_id' => $sceance->id,
                        'student_id' => $studentId,
                    ],
                    [
                        'status'    => $isPresent ? 'present' : 'absent',
                        'confiance' => $isPresent ? ($studentInput['confiance'] ?? null) : null,
                    ]
                );
            }
        });

        SendAttendanceEmailsJob::dispatch($sceance);
        return response()->json([
            'message' => 'Pointage enregistré et notifications envoyées'
        ]);
    }

    // Ajout justificatif
    public function uploadJustificatif(Request $request, Attendance $attendance)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'notes' => 'nullable|string',
        ]);

        $path = $request->file('file')
            ->store('justificatifs', 'public');

        $attendance->update([
            'justificatif' => $path,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'message' => 'Justificatif ajouté'
        ]);
    }
}
