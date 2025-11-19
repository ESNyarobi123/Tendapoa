<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PushNotification extends Model
{
    protected $fillable = [
        'sent_by',
        'title',
        'body',
        'fcm_tokens',
        'total_recipients',
        'successful_sends',
        'failed_sends',
        'errors',
        'status',
        'sent_at',
    ];

    protected $casts = [
        'fcm_tokens' => 'array',
        'errors' => 'array',
        'sent_at' => 'datetime',
        'total_recipients' => 'integer',
        'successful_sends' => 'integer',
        'failed_sends' => 'integer',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}
