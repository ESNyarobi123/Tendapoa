<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id','order_id','amount','status','resultcode','reference',
        'channel','msisdn','transid','meta'
    ];

    protected $casts = ['meta'=>'array'];

    public function job() { return $this->belongsTo(Job::class, 'work_order_id'); }
}
