<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $applications = Application::with(['job.company'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        return response()->json($applications);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'cover_letter' => 'nullable|string',
            'resume_url' => 'nullable|url|max:255'
        ]);

        $application = Application::create([
            'user_id' => auth()->id(),
            'job_id' => $request->job_id,
            'cover_letter' => $request->cover_letter,
            'resume_url' => $request->resume_url,
            'status' => 'pending'
        ]);

        return response()->json($application->load(['job.company', 'user']), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Application $application)
    {
        return response()->json($application->load(['job.company', 'user']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'string|in:pending,reviewing,accepted,rejected',
            'notes' => 'nullable|string'
        ]);

        $application->update($request->all());
        return response()->json($application);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Application $application)
    {
        $application->delete();
        return response()->json(null, 204);
    }

    public function byUser(User $user)
    {
        $applications = $user->applications()
            ->with(['job.company'])
            ->latest()
            ->paginate(10);
        return response()->json($applications);
    }

    public function byJob(Job $job)
    {
        $applications = $job->applications()
            ->with(['user'])
            ->latest()
            ->paginate(10);
        return response()->json($applications);
    }
}
