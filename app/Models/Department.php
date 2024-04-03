<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Department extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'department';
    protected $fillable = [        
        'did',
        'name'
    ];

    
    public function department()
    {
        return $this->hasMany('App\User');
    }
    public function department_users() 
    {
        return $this->belongsTo('App\User');
    }
}
