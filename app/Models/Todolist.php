<?php

namespace App\Models;

use App\Models\Todo;
use App\Models\Owner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todolist extends Model
{
    use HasFactory;

    protected $guarded =[];

    /**
     * Get all of the todos for the Todolist
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function todos()
    {
        return $this->hasMany(Todo::class, 'todolist_id', 'id');
    }
    /**
     * Get the owner that owns the Todolist
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class, 'owner_id', 'id');
    }
}
