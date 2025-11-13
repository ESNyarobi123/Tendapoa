<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Job;

class NotificationService
{
    /**
     * Create a notification for a user
     */
    public function create(int $userId, string $type, string $title, string $message, array $data = []): Notification
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * MFANYAKAZI: Notify when new job is posted
     */
    public function notifyNewJobPosted(Job $job, array $workerIds = [])
    {
        // If no specific workers provided, notify all mfanyakazi
        if (empty($workerIds)) {
            $workerIds = User::where('role', 'mfanyakazi')->pluck('id')->toArray();
        }

        foreach ($workerIds as $workerId) {
            $this->create(
                $workerId,
                Notification::TYPE_JOB_AVAILABLE,
                'Kazi Mpya Imepostwa! 🎉',
                "Kazi mpya: {$job->title}. Bei: TZS " . number_format($job->price),
                [
                    'job_id' => $job->id,
                    'job_title' => $job->title,
                    'job_price' => $job->price,
                    'category' => $job->category->name ?? null,
                ]
            );
        }
    }

    /**
     * MFANYAKAZI: Notify when assigned to a job
     */
    public function notifyWorkerAssigned(Job $job, User $worker)
    {
        $this->create(
            $worker->id,
            Notification::TYPE_WORKER_ASSIGNED,
            'Umechaguliwa Kufanya Kazi! 🎯',
            "Umechaguliwa kufanya kazi: {$job->title}. Angalia na ukubali au ukatae.",
            [
                'job_id' => $job->id,
                'job_title' => $job->title,
                'muhitaji_name' => $job->muhitaji->name ?? null,
            ]
        );
    }

    /**
     * MFANYAKAZI: Notify when they accept a job
     */
    public function notifyWorkerAccepted(Job $job, User $worker)
    {
        $this->create(
            $worker->id,
            Notification::TYPE_JOB_ACCEPTED,
            'Umekubali Kazi! ✅',
            "Umekubali kufanya kazi: {$job->title}. Anza kufanya kazi sasa!",
            [
                'job_id' => $job->id,
                'job_title' => $job->title,
            ]
        );
    }

    /**
     * MFANYAKAZI: Notify when they decline a job
     */
    public function notifyWorkerDeclined(Job $job, User $worker)
    {
        $this->create(
            $worker->id,
            Notification::TYPE_JOB_DECLINED,
            'Umekataa Kazi ❌',
            "Umekataa kazi: {$job->title}",
            [
                'job_id' => $job->id,
                'job_title' => $job->title,
            ]
        );
    }

    /**
     * MFANYAKAZI: Notify when job is completed
     */
    public function notifyWorkerJobCompleted(Job $job, User $worker, float $amount)
    {
        $this->create(
            $worker->id,
            Notification::TYPE_JOB_COMPLETED,
            'Kazi Imekamilika! 🎊',
            "Hongera! Kazi '{$job->title}' imekamilika. Umepokea TZS " . number_format($amount),
            [
                'job_id' => $job->id,
                'job_title' => $job->title,
                'amount' => $amount,
            ]
        );
    }

    /**
     * MUHITAJI: Notify when they post a job
     */
    public function notifyMuhitajiJobPosted(Job $job, User $muhitaji)
    {
        $this->create(
            $muhitaji->id,
            Notification::TYPE_JOB_POSTED,
            'Kazi Yako Imepostwa! 📢',
            "Kazi '{$job->title}' imepostwa kwa mafanikio. Wafanyakazi wataanza kutoa maoni.",
            [
                'job_id' => $job->id,
                'job_title' => $job->title,
            ]
        );
    }

    /**
     * MUHITAJI: Notify when they update a job
     */
    public function notifyMuhitajiJobUpdated(Job $job, User $muhitaji, array $changes = [])
    {
        $this->create(
            $muhitaji->id,
            Notification::TYPE_JOB_UPDATED,
            'Kazi Imebadilishwa! ✏️',
            "Kazi '{$job->title}' imebadilishwa kwa mafanikio.",
            [
                'job_id' => $job->id,
                'job_title' => $job->title,
                'changes' => $changes,
            ]
        );
    }

    /**
     * WAFANYAKAZI: Notify all workers or assigned worker when job is updated
     */
    public function notifyWorkersJobUpdated(Job $job, array $changes = [])
    {
        $changeDescription = $this->formatJobChanges($changes);
        
        // If job has assigned worker, notify only them
        if ($job->accepted_worker_id) {
            $this->create(
                $job->accepted_worker_id,
                Notification::TYPE_JOB_UPDATED,
                'Kazi Imebadilishwa! ✏️',
                "Kazi '{$job->title}' ambayo umeichukua imebadilishwa. {$changeDescription}",
                [
                    'job_id' => $job->id,
                    'job_title' => $job->title,
                    'changes' => $changes,
                    'muhitaji_name' => $job->muhitaji->name ?? null,
                ]
            );
        } else {
            // If job is still open, notify workers who commented/showed interest
            $interestedWorkerIds = \App\Models\JobComment::where('work_order_id', $job->id)
                ->where('user_id', '!=', $job->user_id)
                ->pluck('user_id')
                ->unique()
                ->toArray();

            foreach ($interestedWorkerIds as $workerId) {
                $this->create(
                    $workerId,
                    Notification::TYPE_JOB_UPDATED,
                    'Kazi Imebadilishwa! ✏️',
                    "Kazi '{$job->title}' uliyoonyesha nia imebadilishwa. {$changeDescription}",
                    [
                        'job_id' => $job->id,
                        'job_title' => $job->title,
                        'changes' => $changes,
                    ]
                );
            }
        }
    }

    /**
     * Format job changes for notification message
     */
    private function formatJobChanges(array $changes): string
    {
        $messages = [];
        
        if (isset($changes['price'])) {
            $messages[] = "Bei: TZS " . number_format($changes['price']['old']) . " → TZS " . number_format($changes['price']['new']);
        }
        
        if (isset($changes['title'])) {
            $messages[] = "Jina la kazi limebadilishwa";
        }
        
        if (isset($changes['description'])) {
            $messages[] = "Maelezo yamebadilishwa";
        }
        
        if (isset($changes['location'])) {
            $messages[] = "Mahali pamebadilishwa";
        }
        
        if (isset($changes['category'])) {
            $messages[] = "Kategoria imebadilishwa";
        }
        
        return empty($messages) ? "Angalia mabadiliko." : implode(', ', $messages);
    }

    /**
     * MUHITAJI: Notify when worker accepts their job
     */
    public function notifyMuhitajiWorkerAccepted(Job $job, User $muhitaji, User $worker)
    {
        $this->create(
            $muhitaji->id,
            Notification::TYPE_JOB_ACCEPTED,
            'Mfanyakazi Amekubali! ✅',
            "{$worker->name} amekubali kufanya kazi '{$job->title}'",
            [
                'job_id' => $job->id,
                'job_title' => $job->title,
                'worker_id' => $worker->id,
                'worker_name' => $worker->name,
            ]
        );
    }

    /**
     * MUHITAJI: Notify when worker declines their job
     */
    public function notifyMuhitajiWorkerDeclined(Job $job, User $muhitaji, User $worker)
    {
        $this->create(
            $muhitaji->id,
            Notification::TYPE_JOB_DECLINED,
            'Mfanyakazi Amekataa ❌',
            "{$worker->name} amekataa kazi '{$job->title}'. Chagua mfanyakazi mwingine.",
            [
                'job_id' => $job->id,
                'job_title' => $job->title,
                'worker_id' => $worker->id,
                'worker_name' => $worker->name,
            ]
        );
    }

    /**
     * MUHITAJI: Notify when new comment on their job
     */
    public function notifyMuhitajiNewComment(Job $job, User $muhitaji, User $commenter, string $message)
    {
        $this->create(
            $muhitaji->id,
            Notification::TYPE_NEW_COMMENT,
            'Maoni Mapya! 💬',
            "{$commenter->name} ametoa maoni kwenye kazi '{$job->title}'",
            [
                'job_id' => $job->id,
                'job_title' => $job->title,
                'commenter_id' => $commenter->id,
                'commenter_name' => $commenter->name,
                'comment_preview' => substr($message, 0, 100),
            ]
        );
    }

    /**
     * MUHITAJI: Notify when job is completed
     */
    public function notifyMuhitajiJobCompleted(Job $job, User $muhitaji, User $worker)
    {
        $this->create(
            $muhitaji->id,
            Notification::TYPE_JOB_COMPLETED,
            'Kazi Imekamilika! 🎊',
            "{$worker->name} amekamilisha kazi '{$job->title}'",
            [
                'job_id' => $job->id,
                'job_title' => $job->title,
                'worker_id' => $worker->id,
                'worker_name' => $worker->name,
            ]
        );
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $notificationId): bool
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->markAsRead();
            return true;
        }
        return false;
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Get unread count for a user
     */
    public function getUnreadCount(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Delete old notifications (older than specified days)
     */
    public function deleteOldNotifications(int $days = 30): int
    {
        return Notification::where('created_at', '<', now()->subDays($days))
            ->where('is_read', true)
            ->delete();
    }
}

