<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TodoTable extends Model
{
    //
    protected $table = 'todo_tables';
    protected $fillable = [
        'name',
        'description',
        'file',
        'due_date',
        'priority',
    ];
   
}
