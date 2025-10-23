<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Schema;

class Job extends Model
{
    use HasFactory;

    // Jedwali halisi (epuka mgongano na queue 'jobs')
    protected $table = 'work_orders';

    /** Ruhusu mass-assignment kwa field muhimu */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'price',
        'lat',
        'lng',
        'address_text',
        'status',
        'accepted_worker_id',
        'published_at',
        'completion_code',
        'completed_at',
        'poster_type',
        'posting_fee',
        // zifuatazo zipo kwa compatibility na schemas tofauti
        'mfanyakazi_response',
        'worker_response',
        'assignee_response',
        'budget',
        'payout',
        'location',
    ];

    /** Casts/Types */
    protected $casts = [
        'published_at' => 'datetime',
        'completed_at' => 'datetime',
        'price'        => 'integer',
        'budget'       => 'integer',
        'payout'       => 'integer',
        'posting_fee'  => 'decimal:2',
        'lat'          => 'float',
        'lng'          => 'float',
    ];

    /** STATUS constants (hiari kutumia) */
    public const S_OFFERED     = 'offered';
    public const S_ASSIGNED    = 'assigned';
    public const S_IN_PROGRESS = 'in_progress';
    public const S_READY_FOR_CONFIRMATION = 'ready_for_confirmation';
    public const S_COMPLETED   = 'completed';

    /* ===========================
     |  Mahusiano
     * =========================== */
    public function muhitaji()       { return $this->belongsTo(User::class, 'user_id'); }
    public function acceptedWorker() { return $this->belongsTo(User::class, 'accepted_worker_id'); }
    public function category()       { return $this->belongsTo(Category::class); }

    // imerudi kwenye jedwali jipya work_order_comments
    public function comments()
    {
        return $this->hasMany(JobComment::class, 'work_order_id')->latest();
    }

    // payments.work_order_id
    public function payment()
    {
        return $this->hasOne(Payment::class, 'work_order_id');
    }

    // private messages for this job
    public function privateMessages()
    {
        return $this->hasMany(PrivateMessage::class, 'work_order_id')->latest();
    }

    /* ===========================
     |  Scopes zinazosaidia Controllers
     * =========================== */
    // Kazi zilizo "hai" kwa mfanyakazi husika (assigned + in_progress + ready_for_confirmation)
    public function scopeActiveForMfanyakazi($q, int $mfanyakaziId)
    {
        return $q->where('accepted_worker_id', $mfanyakaziId)
                 ->whereIn('status', [self::S_ASSIGNED, self::S_IN_PROGRESS, self::S_READY_FOR_CONFIRMATION]);
    }

    public function scopeAssignedTo($q, int $mfanyakaziId)
    {
        return $q->where('accepted_worker_id', $mfanyakaziId);
    }

    /* ===========================
     |  Helpers / Accessors
     * =========================== */

    // Amount inayopatikana (priority: payout -> price -> budget)
    public function getAmountAttribute(): int
    {
        return (int)($this->payout ?? $this->price ?? $this->budget ?? 0);
    }

    // Soma response bila kujali jina la column (mfanyakazi_response > worker_response > assignee_response)
    public function getMfanyakaziResponseAttribute($value)
    {
        if (!is_null($value)) return $value;

        // Ikiwa column ya juu haipo kwenye schema/record, jaribu nyingine
        if (array_key_exists('worker_response', $this->attributes) && $this->attributes['worker_response'] !== null) {
            return $this->attributes['worker_response'];
        }
        if (array_key_exists('assignee_response', $this->attributes) && $this->attributes['assignee_response'] !== null) {
            return $this->attributes['assignee_response'];
        }
        return null;
    }

    // Andika response kwenye column yoyote iliyopo (kipaumbele: mfanyakazi_response > worker_response > assignee_response)
    public function setMfanyakaziResponseAttribute($value): void
    {
        // Ikiwa kolamu ipo kabisa kwenye DB, andika humo; la sivyo tunafall-back kwenye nyingine
        if (Schema::hasColumn($this->table, 'mfanyakazi_response')) {
            $this->attributes['mfanyakazi_response'] = $value;
            return;
        }
        if (Schema::hasColumn($this->table, 'worker_response')) {
            $this->attributes['worker_response'] = $value;
            return;
        }
        if (Schema::hasColumn($this->table, 'assignee_response')) {
            $this->attributes['assignee_response'] = $value;
            return;
        }
        // Hakuna kolamu yoyote—tulia kimya (usi-sabotage save())
    }

    // Helper: jina la kolamu ya response itakayotumika (kwa Controllers ikiwa watataka)
    public static function responseColumn(): ?string
    {
        $table = (new static)->getTable();
        if (Schema::hasColumn($table, 'mfanyakazi_response')) return 'mfanyakazi_response';
        if (Schema::hasColumn($table, 'worker_response'))     return 'worker_response';
        if (Schema::hasColumn($table, 'assignee_response'))   return 'assignee_response';
        return null;
    }
}
