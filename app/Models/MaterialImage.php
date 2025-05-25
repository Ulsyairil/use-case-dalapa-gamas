<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class MaterialImage extends Model
{
    use HasFactory;

    protected $table      = 'material_image';
    protected $primaryKey = 'id';
    protected $keyType    = 'string';
    public $incrementing  = false;

    protected $fillable = [
        'material_id',
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

    public function material() {
        return $this->hasOne(Material::class, 'id', 'material_id');
    }

    public function image() {
        return $this->hasOne(Image::class, 'id', 'image_id');
    }
}
