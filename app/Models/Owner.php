<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Todolist;

class Owner extends Model
{
    use HasFactory;

    /**
     * hide password attribute
     */
    protected $hidden = [
        'password',
    ];


    /**
     * Get all of the todolists for the Owner
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function todolists()
    {
        return $this->hasMany(Todolist::class, 'id', 'owner_id');
    }

    /**
     * Get all of the todos for the Owner
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function todos(): HasManyThrough
    {
        return $this->hasManyThrough(Todo::class, Todolist::class);
    }
}
