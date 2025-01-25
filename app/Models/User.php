<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Automatyczne wypełnianie timestampów
    public $timestamps = true;

    // Relacja z innymi tabelami
    public function jobs()
    {
        return $this->hasMany(Job::class, 'posted_by_user_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
