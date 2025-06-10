<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Company;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobs = Job::with('company')
            ->where('is_active', true)
            ->latest()
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
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gt:salary_min',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string'
        ]);

        $job = Job::create($request->all());
        return response()->json($job, 201);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        $job->delete();
        return response()->json(null, 204);
    }

    public function byCompany(Company $company)
    {
        $jobs = $company->jobs()
            ->where('is_active', true)
            ->latest()
            ->paginate(10);
        return response()->json($jobs);
    }
}
