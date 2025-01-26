<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\JobApplication;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $application = JobApplication::create([
            'user_id' => $request['userId'],
            'job_id' => $request['jobId'],
            'first_name' => $request['firstName'],
            'last_name' => $request['lastName'],
            'email' => $request['applyEmail'],
            'resume' => $request['resume'],
        ]);

        if ($application) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'error' => 'Failed to save application'], 500);
    }

    public function getResume($id): JsonResponse
    {
        $application = Application::findOrFail($id);

        return response()->json(['resume' => base64_encode($application->resume)]);
    }
}
