<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Application;

class ResumeController extends Controller
{
    public function show($id): JsonResponse
    {
        $application = Application::find($id);

        if (!$application || !$application->resume) {
            return response()->json(['error' => 'Resume not found.'], 404);
        }

        $resumeBase64 = $application->resume;

        return response()->json(['resume' => $resumeBase64]);
    }
}
