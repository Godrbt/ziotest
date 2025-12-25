<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class TodoTable extends Model
{
    //
    use Prunable;

    public function prunable()
    {
        return static::where('due_date', '<=', now()->subDays(7));
    }



    protected $table = 'todo_tables';
    protected $fillable = [
        'name',
        'description',
        'file',
        'due_date',
        'priority',
    ];
   
}
