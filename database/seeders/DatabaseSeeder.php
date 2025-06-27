<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\JobType;
use App\Models\Job;
use App\Models\Role;
use App\Models\Permission;
use App\Models\EmployerProfile;
use App\Models\JobSeekerProfile;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default roles
        $roles = ['superadmin', 'admin', 'employer', 'jobseeker'];
        foreach ($roles as $role) {
            \App\Models\Role::create([
                'name' => ucfirst($role),
                'description' => ucfirst($role) . ' role'
            ]);
        }

        // Create default job types
        \App\Models\JobType::factory(5)->create();

        // Create default categories
        \App\Models\Category::factory(10)->create();

        // Create permissions
        $permissions = [
            // User management
            ['name' => 'view-users', 'description' => 'Can view users', 'group' => 'user'],
            ['name' => 'create-users', 'description' => 'Can create users', 'group' => 'user'],
            ['name' => 'edit-users', 'description' => 'Can edit users', 'group' => 'user'],
            ['name' => 'delete-users', 'description' => 'Can delete users', 'group' => 'user'],
            
            // Job management
            ['name' => 'view-jobs', 'description' => 'Can view jobs', 'group' => 'job'],
            ['name' => 'create-jobs', 'description' => 'Can create jobs', 'group' => 'job'],
            ['name' => 'edit-jobs', 'description' => 'Can edit jobs', 'group' => 'job'],
            ['name' => 'delete-jobs', 'description' => 'Can delete jobs', 'group' => 'job'],
            
            // Category management
            ['name' => 'manage-categories', 'description' => 'Can manage job categories', 'group' => 'category'],
            
            // Job type management
            ['name' => 'manage-job-types', 'description' => 'Can manage job types', 'group' => 'job-type'],
            
            // Application management
            ['name' => 'apply-jobs', 'description' => 'Can apply for jobs', 'group' => 'application'],
            ['name' => 'manage-applications', 'description' => 'Can manage job applications', 'group' => 'application']
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Assign permissions to roles
        $superAdmin = Role::where('name', 'Superadmin')->first();
        $admin = Role::where('name', 'Admin')->first();
        $employer = Role::where('name', 'Employer')->first();
        $jobSeeker = Role::where('name', 'Jobseeker')->first();

        // Super Admin gets all permissions
        $superAdmin->permissions()->attach(Permission::all());

        // Admin gets most permissions except super admin specific ones
        $admin->permissions()->attach(Permission::whereNotIn('name', ['create-users'])->get());

        // Employer permissions
        $employer->permissions()->attach(Permission::whereIn('name', [
            'view-jobs',
            'create-jobs',
            'edit-jobs',
            'delete-jobs',
            'manage-applications'
        ])->get());

        // Job Seeker permissions
        $jobSeeker->permissions()->attach(Permission::whereIn('name', [
            'view-jobs',
            'apply-jobs'
        ])->get());

        // Create sample users for each role
        
        // 1. Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('superadmin123'),
            'role' => 'superadmin',
            'email_verified_at' => now(),
            'is_active' => true
        ]);
        $superAdmin->roles()->attach(Role::where('name', 'Superadmin')->first()->id);

        // 2. Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'is_active' => true
        ]);
        $admin->roles()->attach(Role::where('name', 'Admin')->first()->id);

        // 3. Sample Employers
        $employer1 = User::create([
            'name' => 'John Smith',
            'email' => 'employer1@example.com',
            'password' => bcrypt('employer123'),
            'role' => 'employer',
            'email_verified_at' => now(),
            'is_active' => true
        ]);
        $employer1->roles()->attach(Role::where('name', 'Employer')->first()->id);
        
        // Create employer profile
        EmployerProfile::create([
            'user_id' => $employer1->id,
            'company_name' => 'Tech Solutions Inc',
            'company_description' => 'Leading technology solutions provider',
            'industry' => 'Information Technology',
            'company_size' => '50-200',
            'website' => 'https://techsolutions.example.com',
            'is_verified' => true
        ]);

        $employer2 = User::create([
            'name' => 'Sarah Johnson',
            'email' => 'employer2@example.com',
            'password' => bcrypt('employer123'),
            'role' => 'employer',
            'email_verified_at' => now(),
            'is_active' => true
        ]);
        $employer2->roles()->attach(Role::where('name', 'Employer')->first()->id);
        
        // Create employer profile
        EmployerProfile::create([
            'user_id' => $employer2->id,
            'company_name' => 'Global Marketing Group',
            'company_description' => 'International marketing and advertising agency',
            'industry' => 'Marketing',
            'company_size' => '20-50',
            'website' => 'https://globalmarketing.example.com',
            'is_verified' => true
        ]);

        // 4. Sample Job Seekers
        $jobSeeker1 = User::create([
            'name' => 'David Wilson',
            'email' => 'jobseeker1@example.com',
            'password' => bcrypt('jobseeker123'),
            'role' => 'jobseeker',
            'email_verified_at' => now(),
            'is_active' => true
        ]);
        $jobSeeker1->roles()->attach(Role::where('name', 'Jobseeker')->first()->id);
        
        // Create job seeker profile
        JobSeekerProfile::create([
            'user_id' => $jobSeeker1->id,
            'skills' => json_encode(['PHP', 'Laravel', 'MySQL', 'JavaScript']),
            'experience' => '5 years of web development experience',
            'education' => json_encode(['Bachelor in Computer Science']),
            'current_salary' => '50000',
            'expected_salary' => '65000',
            'preferred_location' => 'New York',
            'is_kyc_verified' => true
        ]);

        $jobSeeker2 = User::create([
            'name' => 'Emily Brown',
            'email' => 'jobseeker2@example.com',
            'password' => bcrypt('jobseeker123'),
            'role' => 'jobseeker',
            'email_verified_at' => now(),
            'is_active' => true
        ]);
        $jobSeeker2->roles()->attach(Role::where('name', 'Jobseeker')->first()->id);
        
        // Create job seeker profile
        JobSeekerProfile::create([
            'user_id' => $jobSeeker2->id,
            'skills' => json_encode(['Marketing', 'Social Media', 'Content Writing']),
            'experience' => '3 years of marketing experience',
            'education' => json_encode(['Bachelor in Marketing']),
            'current_salary' => '45000',
            'expected_salary' => '55000',
            'preferred_location' => 'Los Angeles',
            'is_kyc_verified' => true
        ]);

        // Create test users
        \App\Models\User::factory(50)->create()->each(function ($user) {
            if ($user->role === 'employer') {
                EmployerProfile::create([
                    'user_id' => $user->id,
                    'company_name' => fake()->company(),
                    'company_description' => fake()->paragraph(),
                    'industry' => fake()->randomElement(['IT', 'Marketing', 'Finance', 'Healthcare', 'Education']),
                    'company_size' => fake()->randomElement(['1-10', '11-50', '51-200', '201-500', '500+']),
                    'website' => fake()->url(),
                    'is_verified' => fake()->boolean(70)
                ]);
            } else {
                JobSeekerProfile::create([
                    'user_id' => $user->id,
                    'skills' => json_encode(fake()->words(4)),
                    'experience' => fake()->paragraph(),
                    'education' => json_encode([fake()->sentence()]),
                    'current_salary' => fake()->numberBetween(30000, 100000),
                    'expected_salary' => fake()->numberBetween(40000, 120000),
                    'preferred_location' => fake()->city(),
                    'is_kyc_verified' => fake()->boolean(50)
                ]);
            }
        });

        // Create test jobs
        \App\Models\Job::factory(100)->create();
    }
}
