<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Todolist;

class Todo extends Model
{
    use HasFactory;

    protected $guarded =[];
    

    public function todolist (){
        return $this->belongsTo(Todolist::class);
    }
}
