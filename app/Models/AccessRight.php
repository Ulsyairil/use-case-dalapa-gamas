<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class AccessRight extends Model
{
    use HasFactory, SoftDeletes;

    protected static $currentUser = null;

    protected $table      = 'access_right';
    protected $primaryKey = 'id';
    protected $keyType    = 'string';
    public $incrementing  = false;

    protected $fillable = [
        'id',
        'access_name',
        'access_description',
        'created_by',
        'updated_by',
        'deleted_by',
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

    public function access_detail() {
        return $this->hasMany(AccessRightDetail::class, 'access_id', 'id');
    }
}
