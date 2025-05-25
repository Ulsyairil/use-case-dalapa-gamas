<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class AdminToken extends Model
{
    use HasFactory;

    protected $table      = 'admin_token';
    protected $primaryKey = 'id';
    protected $keyType    = 'string';
    public $incrementing  = false;

    protected $fillable = [
        'id',
        'admin_id',
        'token',
        'expired_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Uuid::uuid7()->toString();
        });
    }

    public function admin() {
        return $this->hasOne(Admin::class, 'id', 'admin_id');
    }
}
