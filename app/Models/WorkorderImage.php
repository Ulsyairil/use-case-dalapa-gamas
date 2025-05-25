<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class WorkorderImage extends Model
{
    use HasFactory;

    protected $table      = 'workorder_image';
    protected $primaryKey = 'id';
    protected $keyType    = 'string';
    public $incrementing  = false;

    protected $fillable = [
        'id',
        'workorder_id',
        'image_id',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Uuid::uuid7()->toString();
        });
    }

    public function workorder() {
        return $this->hasOne(Workorder::class, 'id', 'workorder_id');
    }

    public function image() {
        return $this->hasOne(Image::class, 'id', 'image_id');
    }
}
