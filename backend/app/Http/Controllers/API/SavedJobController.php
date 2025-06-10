<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\SavedJob;
use Illuminate\Http\Request;

class SavedJobController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Get user's saved jobs
     */
    public function index(Request $request)
    {
        $savedJobs = $request->user()
            ->savedJobs()
            ->with('job.company')
            ->latest()
            ->get()
            ->pluck('job');

        return response()->json($savedJobs);
    }

    /**
     * Save a job
     */
    public function store(Request $request, Job $job)
    {
        $request->user()->savedJobs()->firstOrCreate([
            'job_id' => $job->id
        ]);

        return response()->json(['message' => 'Job saved successfully']);
    }

    /**
     * Unsave a job
     */
    public function destroy(Request $request, Job $job)
    {
        $request->user()->savedJobs()->where('job_id', $job->id)->delete();

        return response()->json(['message' => 'Job removed from saved list']);
    }
} 