<?php

namespace App\Models;

use App\Models\Scopes\ActiveProjectScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'deadline',
        'status',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new ActiveProjectScope);
    }

    public const STATUS = ['open', 'in progress', 'pending', 'waiting client', 'blocked', 'closed'];

    public function project() {
        return $this->belongsTo(Project::class);
    }
}
