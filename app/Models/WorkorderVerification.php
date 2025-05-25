<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class WorkorderVerification extends Model
{
    use HasFactory;

    protected static $currentUser = null;

    protected $table      = 'wo_verification';
    protected $primaryKey = 'id';
    protected $keyType    = 'string';
    public $incrementing  = false;

    const DISETUJUI = 'disetujui';
    const DITOLAK   = 'ditolak';

    protected $fillable = [
        'id',
        'workorder_id',
        'status',
        'note',
        'created_by',
        'updated_by',
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
            $model->id         = Uuid::uuid7()->toString();
            $model->created_by = self::$currentUser;
        });

        static::updating(function ($model) {
            $model->updated_by = self::$currentUser;
        });
    }

    public static function setCurrentUser($userId) {
        self::$currentUser = $userId;
    }

    public function workorder() {
        return $this->hasOne(Workorder::class, 'id', 'workorder_id');
    }
}
