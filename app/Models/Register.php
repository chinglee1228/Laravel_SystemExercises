<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    use HasFactory;
    protected $table = 'users';
    protected $fillable = [
        'did',
        'name',
        'username',
        'password'];
       
    public function department()
    {
        return $this->belongsTo(Department::class, 'did', 'did');//前方為department的主鍵 後方為register 該model的主鍵
    }

    
}
