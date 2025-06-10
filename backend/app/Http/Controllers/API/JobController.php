<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Company;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
        $this->middleware(function ($request, $next) {
            if (!$request->user()->isEmployer()) {
                return response()->json(['message' => 'Only employers can perform this action'], 403);
            }
            return $next($request);
        })->only(['store', 'update', 'destroy', 'restore']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // If authenticated and employer, return their jobs
        if ($user && $user->isEmployer()) {
            // Check if company exists
            if (!$user->company) {
                return response()->json([
                    'message' => 'Please create a company profile first',
                    'needs_company' => true
                ], 403);
            }

            $jobs = Job::where('company_id', $user->company->id)
                ->with('company')
                ->latest()
                ->get();
            return response()->json($jobs);
        }

        // Otherwise return public jobs
        $filters = $request->validate([
            'search' => 'nullable|string',
            'location' => 'nullable|string',
            'type' => 'nullable|string',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'company_id' => 'nullable|exists:companies,id',
            'is_active' => 'nullable|boolean'
        ]);

        $jobs = Job::with('company')
            ->where('status', 'active')
            ->filter($filters)
            ->latest()
            ->paginate(10);

        return response()->json($jobs);
    }

    /**
     * Get recommended jobs for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function recommended(Request $request)
    {
        $user = $request->user();
        
        if (!$user->isJobSeeker()) {
            return response()->json(['message' => 'Only job seekers can get job recommendations'], 403);
        }

        $jobs = Job::with('company')
            ->recommended($user)
            ->paginate(10);

        return response()->json($jobs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        
        // Check if the user has a company
        $company = $user->company;
        if (!$company) {
            return response()->json(['message' => 'Please create a company profile first'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gt:salary_min',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string'
        ]);

        // Create job with the user's company
        $job = Job::create(array_merge(
            $request->all(),
            ['company_id' => $company->id]
        ));

        return response()->json($job->load('company'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
    {
        return response()->json($job->load('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job $job)
    {
        $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'location' => 'string|max:255',
            'type' => 'string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gt:salary_min',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $job->update($request->all());
        return response()->json($job);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        $job->delete();
        return response()->json(null, 204);
    }

    /**
     * Restore a soft-deleted job.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $job = Job::withTrashed()->findOrFail($id);
        $job->restore();
        return response()->json($job);
    }

    /**
     * Get jobs by company.
     *
     * @param  Company  $company
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function byCompany(Company $company, Request $request)
    {
        $filters = $request->validate([
            'search' => 'nullable|string',
            'type' => 'nullable|string',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean'
        ]);

        $jobs = $company->jobs()
            ->filter($filters)
            ->latest()
            ->paginate(10);
            
        return response()->json($jobs);
    }
}
