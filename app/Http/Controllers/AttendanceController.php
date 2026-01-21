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
            return response()->json(['message' => 'La séance n’est pas active'], 403);
        }

        $inputMap = collect($data['students'])->keyBy(fn($item) => (int) $item['id']);

        DB::transaction(function () use ($sceance, $inputMap) {
            foreach ($sceance->classe->students as $student) {

                $studentInput = $inputMap->get((int) $student->id);

                if ($studentInput) {
                    $isPresent = filter_var($studentInput['present'], FILTER_VALIDATE_BOOLEAN);
                    $status = $isPresent ? 'present' : 'absent';
                    $confiance = $isPresent ? ($studentInput['confiance'] ?? null) : null;
                } else {
                    $status = 'absent';
                    $confiance = null;
                }

                Attendance::updateOrCreate(
                    [
                        'sceance_id' => $sceance->id,
                        'student_id' => $student->id,
                    ],
                    [
                        'status'    => $status,
                        'confiance' => $confiance,
                    ]
                );
            }
        });

        SendAttendanceEmailsJob::dispatch($sceance);

        return response()->json(['message' => 'Pointage enregistré pour toute la classe']);
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
