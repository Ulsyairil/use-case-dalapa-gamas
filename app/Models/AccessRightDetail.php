<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class AccessRightDetail extends Model
{
    use HasFactory, SoftDeletes;
    
    protected static $currentUser = null;

    protected $table      = 'access_right_detail';
    protected $primaryKey = 'id';
    protected $keyType    = 'string';
    public $incrementing  = false;

    protected $fillable = [
        'id',
        'access_id',
        'access_code',
        'create',
        'read',
        'update',
        'delete',
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
        'create'     => 'boolean',
        'read'       => 'boolean',
        'update'     => 'boolean',
        'delete'     => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public static function boot() {
        parent::boot();

        self::creating(function ($model) {
            $model->id         = Uuid::uuid7()->toString();
            $model->created_by = self::$currentUser;
        });
        
        self::updating(function ($model) {
            $model->updated_by = self::$currentUser;
        });

        self::deleting(function ($model) {
            $model->deleted_by = self::$currentUser;
        });
    }

    public static function setCurrentUser($userId) {
        self::$currentUser = $userId;
    }

    public function access_name() {
        return $this->hasOne(AccessRight::class, 'id', 'access_id');
    }
}
