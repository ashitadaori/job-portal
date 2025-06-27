<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JobType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'status'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($jobType) {
            $jobType->slug = Str::slug($jobType->name);
        });

        static::updating(function ($jobType) {
            $jobType->slug = Str::slug($jobType->name);
        });
    }
}
