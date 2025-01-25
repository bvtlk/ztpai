<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory
    {
        $locations = Job::distinct('location')->pluck('location');
        $jobs = Job::all();

        $applications = [];

        if (session('user_id')) {
            $userId = session('user_id');

            foreach ($jobs as $job) {
                if ($job->posted_by_user_id == $userId) {
                    $jobsIdApplication[] = $job->id;
                }
            }

            $applications = Application::with('job:title,id')
                ->whereIn('job_id', $jobsIdApplication ?? [])
                ->get();
        }

        return view('jobs.index', compact('locations', 'jobs', 'applications'));
    }


    public function filter(Request $request): JsonResponse
    {
        try {
            // Filtracja ofert pracy
            $jobs = Job::query()
                ->when($request['location'], function ($query, $location) {
                    return $query->where('location', $location);
                })
                ->when($request['salaryMin'], function ($query, $salaryMin) {
                    return $query->where('salary_from', '>=', $salaryMin);
                })
                ->when($request['searchTerm'], function ($query, $searchTerm) {
                    return $query->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($searchTerm) . '%']);
                })
                ->orderBy($request['sortField'] ?? 'created_at', $request['sortOrder'] ?? 'asc')
                ->get();

            return response()->json($jobs);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $job = Job::find($id);

        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        return response()->json($job);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'salary_from' => 'required|integer|min:0',
            'salary_to' => 'required|integer|min:0',
            'tags' => 'required|string',
            'posted_by_user_id' => 'required|integer|min:0',
        ]);

        $job = Job::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'posted_by_user_id' => $validated['posted_by_user_id'],
            'company' => $validated['company'],
            'location' => $validated['location'],
            'salary_from' => $validated['salary_from'],
            'salary_to' => $validated['salary_to'],
            'tags' => $validated['tags'],
            'created_at' => now(),
        ]);

        if ($job) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Failed to post job'], 500);
    }

    public function apply(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'job_id' => 'required|integer|exists:jobs,id',
        ]);

        $application = new \App\Models\Application();
        $application->first_name = $request->first_name;
        $application->last_name = $request->last_name;
        $application->email = $request->email;
        $application->job_id = $request->job_id;

        if ($request->hasFile('resume')) {
            $path = $request->file('resume')->store('resumes');
            $application->resume_path = $path;
        }

        $application->save();

        return redirect()->back()->with('success', 'Your application has been submitted successfully.');
    }

}
