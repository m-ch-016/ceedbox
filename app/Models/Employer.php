<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employer extends Model
{
    use HasFactory;


    protected $table = 'employers';

    protected $fillable = ['id', 'name', 'user_id'];

    public function jobs(): HasMany {
        return $this->hasMany(Job::class);
    }

    public function user(): BelongsTo {
        return $this->BelongsTo(User::class);
    }


    // public function employer(): BelongsTo
    // {
    //     return $this->belongsTo(Employer::class);
    // }
}
