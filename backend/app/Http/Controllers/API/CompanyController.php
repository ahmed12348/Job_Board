<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
        $this->middleware(function ($request, $next) {
            if (!$request->user()->isEmployer()) {
                return response()->json(['message' => 'Only employers can perform this action'], 403);
            }
            return $next($request);
        })->only(['store', 'update', 'destroy']);
    }

    /**
     * Get the authenticated user's company profile
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function myCompany(Request $request)
    {
        $user = $request->user();
        
        if (!$user->isEmployer()) {
            return response()->json(['message' => 'Only employers can have company profiles'], 403);
        }

        $company = $user->company;
        
        if (!$company) {
            return response()->json([
                'message' => 'You haven\'t created a company profile yet',
                'has_company' => false
            ], 404);
        }

        return response()->json([
            'company' => $company->load('jobs'),
            'has_company' => true
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::withCount('jobs')
            ->latest()
            ->paginate(10);
        return response()->json($companies);
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

        // Check if user already has a company
        if ($user->company) {
            return response()->json([
                'message' => 'You already have a company profile'
            ], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|url|max:255',
            'location' => 'required|string|max:255'
        ]);

        $company = Company::create(array_merge(
            $request->all(),
            ['user_id' => $user->id]
        ));

        return response()->json($company, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return response()->json($company->load(['jobs', 'user:id,name,email']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        // Ensure user can only update their own company
        if ($company->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized to update this company'], 403);
        }

        $request->validate([
            'name' => 'string|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|url|max:255',
            'location' => 'string|max:255'
        ]);

        $company->update($request->all());
        return response()->json($company);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        // Ensure user can only delete their own company
        if ($company->user_id !== request()->user()->id) {
            return response()->json(['message' => 'Unauthorized to delete this company'], 403);
        }

        $company->delete();
        return response()->json(null, 204);
    }
}
