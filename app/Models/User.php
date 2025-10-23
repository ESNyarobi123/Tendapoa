<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name','email','password','phone','role','lat','lng','is_active'];
    protected $hidden = ['password','remember_token'];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function jobs(){ return $this->hasMany(Job::class, 'user_id'); } // as muhitaji
    public function assignedJobs(){ return $this->hasMany(Job::class,'accepted_worker_id'); }
    public function comments(){ return $this->hasMany(JobComment::class); }
    public function withdrawals(){ return $this->hasMany(Withdrawal::class); }
    public function sentMessages(){ return $this->hasMany(PrivateMessage::class, 'sender_id'); }
    public function receivedMessages(){ return $this->hasMany(PrivateMessage::class, 'receiver_id'); }
    
    // Additional relationships for admin
    public function muhitaji(){ return $this->hasMany(Job::class, 'user_id'); }
    public function mfanyakazi(){ return $this->hasMany(Job::class, 'accepted_worker_id'); }

    // Wallet
    public function wallet(){ return $this->hasOne(Wallet::class); }
    public function ensureWallet(): Wallet
    {
        return $this->wallet()->firstOrCreate([], ['balance'=>0]);
    }
}
