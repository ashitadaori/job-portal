<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Carbon\Carbon;

class AdminSettingsController extends Controller
{
    public function index()
    {
        // Get site settings
        $settings = DB::table('site_settings')->get()->keyBy('key');
        
        // Get security logs
        $securityLogs = DB::table('security_logs')
            ->join('users', 'security_logs.user_id', '=', 'users.id')
            ->select('security_logs.*', 'users.name')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get user roles and permissions
        $roles = DB::table('roles')->get();
        $permissions = DB::table('permissions')->get();

        // Get backup status
        $backupStatus = [
            'last_backup' => DB::table('backups')->latest()->first()?->created_at ?? 'No backups',
            'backup_size' => DB::table('backups')->sum('size'),
            'total_backups' => DB::table('backups')->count(),
        ];

        return view('admin.settings.index', compact(
            'settings',
            'securityLogs',
            'roles',
            'permissions',
            'backupStatus'
        ));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'required|string',
            'contact_email' => 'required|email',
            'jobs_per_page' => 'required|integer|min:5|max:100',
            'enable_job_alerts' => 'boolean',
            'maintenance_mode' => 'boolean',
            'enable_user_registration' => 'boolean',
        ]);

        // Update settings
        foreach ($request->except('_token') as $key => $value) {
            DB::table('site_settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => now()]
            );
        }

        // Clear cache
        Cache::tags(['settings'])->flush();

        // Log the change
        $this->logSecurityEvent('Site settings updated', $request->user()->id);

        return redirect()->back()->with('success', 'Settings updated successfully');
    }

    public function roles()
    {
        $roles = DB::table('roles')->get();
        $permissions = DB::table('permissions')->get();
        
        return view('admin.settings.roles', compact('roles', 'permissions'));
    }

    public function updateRole(Request $request, $roleId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        DB::transaction(function () use ($request, $roleId) {
            // Update role
            DB::table('roles')->where('id', $roleId)->update([
                'name' => $request->name,
                'updated_at' => now()
            ]);

            // Sync permissions
            DB::table('role_permissions')->where('role_id', $roleId)->delete();
            foreach ($request->permissions as $permissionId) {
                DB::table('role_permissions')->insert([
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                    'created_at' => now()
                ]);
            }
        });

        $this->logSecurityEvent("Role {$request->name} updated", $request->user()->id);

        return redirect()->back()->with('success', 'Role updated successfully');
    }

    public function auditLog()
    {
        $logs = DB::table('audit_logs')
            ->join('users', 'audit_logs.user_id', '=', 'users.id')
            ->select('audit_logs.*', 'users.name')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.settings.audit-log', compact('logs'));
    }

    public function securityLog()
    {
        $logs = DB::table('security_logs')
            ->join('users', 'security_logs.user_id', '=', 'users.id')
            ->select('security_logs.*', 'users.name')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.settings.security-log', compact('logs'));
    }

    public function backup()
    {
        $backups = DB::table('backups')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.settings.backup', compact('backups'));
    }

    public function createBackup(Request $request)
    {
        try {
            // Trigger backup job
            dispatch(new \App\Jobs\CreateBackupJob());
            
            $this->logSecurityEvent('Manual backup initiated', $request->user()->id);
            
            return redirect()->back()->with('success', 'Backup job initiated successfully');
        } catch (\Exception $e) {
            Log::error('Backup failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create backup');
        }
    }

    protected function logSecurityEvent($event, $userId)
    {
        DB::table('security_logs')->insert([
            'event' => $event,
            'user_id' => $userId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now()
        ]);
    }
} 