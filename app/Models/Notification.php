<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Notification types constants
    public const TYPE_JOB_POSTED = 'job_posted';
    public const TYPE_JOB_ACCEPTED = 'job_accepted';
    public const TYPE_JOB_DECLINED = 'job_declined';
    public const TYPE_JOB_COMPLETED = 'job_completed';
    public const TYPE_NEW_COMMENT = 'new_comment';
    public const TYPE_WORKER_ASSIGNED = 'worker_assigned';
    public const TYPE_JOB_AVAILABLE = 'job_available';

    /**
     * Relationship: Notification belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the job related to this notification
     */
    public function job()
    {
        $jobId = $this->data['job_id'] ?? null;
        if ($jobId) {
            return Job::find($jobId);
        }
        return null;
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Scope: Get unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope: Get read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope: Get notifications by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: Get recent notifications
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}

