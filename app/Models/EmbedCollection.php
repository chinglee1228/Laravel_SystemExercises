<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//對於EmbedCollection，在此模型中我們需要停用 id 列的自動增量 id 和自動歸檔 uuid：
class EmbedCollection extends Model
{
    use HasFactory;

    public $fillable = ['name', 'meta_data'];
    public $incrementing = false;
    public $keyType = "string";

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }

    public function toArray()
    {
        $this->meta_data = json_decode($this->meta_data);
        return $this;
    }
}
