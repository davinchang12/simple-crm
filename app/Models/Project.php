<?php

namespace App\Models;

use App\Models\Scopes\ActiveClientScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS = ['open', 'in progress', 'blocked', 'cancelled', 'completed'];

    protected $fillable = [
        'user_id',
        'client_id',
        'title',
        'description',
        'deadline',
        'status',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new ActiveClientScope);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function tasks() {
        return $this->hasMany(Task::class);
    }
}
