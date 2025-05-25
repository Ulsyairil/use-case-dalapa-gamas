<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Admin extends Model
{
    use HasFactory, SoftDeletes;

    protected static $currentUser = null;

    protected $table      = 'admin';
    protected $primaryKey = 'id';
    protected $keyType    = 'string';
    public $incrementing  = false;

    protected $fillable = [
        'fullname',
        'username',
        'nik',
        'email',
        'password',
        'access_id',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $hidden = [
        'password',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->id         = Uuid::uuid7()->toString();
            $model->created_by = self::$currentUser;
        });

        static::updating(function ($model) {
            $model->updated_by = self::$currentUser;
        });

        static::deleting(function ($model) {
            $model->deleted_by = self::$currentUser;
        });
    }

    public static function setCurrentUser($userId) {
        self::$currentUser = $userId;
    }

    public function access() {
        return $this->hasOne(AccessRight::class, 'id', 'access_id');
    }
}
