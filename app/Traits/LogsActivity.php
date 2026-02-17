<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    /**
     * Log an activity
     *
     * @param string $action (created, updated, deleted, viewed)
     * @param string $modelType The type of model being acted upon
     * @param int|null $modelId The ID of the model
     * @param array|null $changes The changes made to the model
     */
    public function logActivity(string $action, string $modelType, $modelId = null, $changes = null)
    {
        try {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'model_type' => $modelType,
                'model_id' => $modelId,
                'changes' => $changes ? json_encode($changes) : null,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        } catch (\Exception $e) {
            // Silently fail - don't let logging errors break the application
            \Log::error('Activity logging failed: ' . $e->getMessage());
        }
    }

    /**
     * Get the difference between old and new values
     */
    public function getChanges($old, $new, $exclude = [])
    {
        $changes = [];

        foreach ($new as $key => $value) {
            if (in_array($key, $exclude)) {
                continue;
            }

            $oldValue = $old[$key] ?? null;

            if ($oldValue !== $value) {
                $changes[$key] = [
                    'before' => $oldValue,
                    'after' => $value,
                ];
            }
        }

        return $changes;
    }
}
