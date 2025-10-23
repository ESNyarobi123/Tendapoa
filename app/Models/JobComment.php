<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobComment extends Model
{
    use HasFactory;

    protected $table = 'work_order_comments';

    protected $fillable = ['work_order_id','user_id','message','is_application','bid_amount'];

    public function job() { return $this->belongsTo(Job::class, 'work_order_id'); }
    public function user(){ return $this->belongsTo(User::class); }
}
