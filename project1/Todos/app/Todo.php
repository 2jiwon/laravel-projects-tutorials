<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Todo extends Model
{
    public function index() {
      return view('todos');
    }

    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
