<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Job extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'location',
        'type',
        'salary_min',
        'salary_max',
        'requirements',
        'benefits',
        'company_id',
        'status'
    ];

    protected $with = ['company'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Scope a query to filter jobs based on search criteria.
     *
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('requirements', 'like', '%' . $search . '%');
            });
        })->when($filters['location'] ?? null, function ($query, $location) {
            $query->where('location', 'like', '%' . $location . '%');
        })->when($filters['type'] ?? null, function ($query, $type) {
            $query->where('type', $type);
        })->when($filters['company_id'] ?? null, function ($query, $companyId) {
            $query->where('company_id', $companyId);
        });
    }

    /**
     * Get recommended jobs based on user preferences and history.
     *
     * @param User $user
     * @return Builder
     */
    public function scopeRecommended(Builder $query, User $user): Builder
    {
        // Get user's application history
        $appliedJobs = $user->applications()->with('job')->get();
        
        // Extract relevant keywords and preferences
        $keywords = collect();
        $locations = collect();
        $jobTypes = collect();
        
        foreach ($appliedJobs as $application) {
            $job = $application->job;
            $keywords->push($job->title, $job->requirements);
            $locations->push($job->location);
            $jobTypes->push($job->type);
        }
        
        // Filter jobs based on user's history
        return $query->where('is_active', true)
            ->where(function ($query) use ($keywords, $locations, $jobTypes) {
                // Match by similar titles or requirements
                foreach ($keywords->unique()->take(5) as $keyword) {
                    $query->orWhere('title', 'like', '%' . $keyword . '%')
                        ->orWhere('requirements', 'like', '%' . $keyword . '%');
                }
                
                // Match by location preference
                if ($locations->isNotEmpty()) {
                    $query->orWhereIn('location', $locations->unique());
                }
                
                // Match by job type preference
                if ($jobTypes->isNotEmpty()) {
                    $query->orWhereIn('type', $jobTypes->unique());
                }
            })
            ->whereNotIn('id', $appliedJobs->pluck('job_id')) // Exclude already applied jobs
            ->orderBy('created_at', 'desc');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
