<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employer_id',
        'category_id',
        'job_type_id',
        'title',
        'description',
        'requirements',
        'benefits',
        'salary_range',
        'location',
        'company_name',
        'company_website',
        'status',
        'featured'
    ];

    public function jobType(){
        return $this->belongsTo(JobType::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    // hasMany method count your total applications
    public function applications(){
        return $this->hasMany(JobApplication::class);
    }

    public function employer(){
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function savedBy(){
        return $this->hasMany(SavedJob::class);
    }
}
