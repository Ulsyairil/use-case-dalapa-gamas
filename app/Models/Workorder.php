<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Workorder extends Model
{
    use HasFactory, SoftDeletes;

    protected static $currentUser = null;

    protected $table      = 'workorder';
    protected $primaryKey = 'id';
    protected $keyType    = 'string';
    public $incrementing  = false;

    const DRAFT        = 'draft';
    const SELESAI      = 'selesai';
    const DIVERIFIKASI = 'diverifikasi';
    const REVISI       = 'revisi';

    protected $fillable = [
        'id',
        'technician_id',
        'ticket_id',
        'status',
        'is_submitted',
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

    public function technician() {
        return $this->hasOne(Technician::class, 'id', 'technician_id');
    }

    public function ticket() {
        return $this->hasOne(TroubleTicket::class, 'id', 'ticket_id');
    }

    public function segment() {
        return $this->hasOne(Segment::class, 'workorder_id', 'id');
    }

    public function technical_information() {
        return $this->hasOne(TechnicalInformation::class, 'workorder_id', 'id');
    }

    public function wo_verification() {
        return $this->hasOne(WorkorderVerification::class, 'workorder_id', 'id');
    }

    public function material_usage() {
        return $this->hasMany(MaterialUsage::class, 'workorder_id', 'id');
    }

    public function wo_image() {
        return $this->hasMany(WorkorderImage::class, 'workorder_id', 'id');
    }
}
