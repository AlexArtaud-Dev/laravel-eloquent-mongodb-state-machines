<?php

namespace Izoniks\LaravelEloquentMongodbStateMachines\Models;

use Carbon\Carbon;
use MongoDB\Laravel\Eloquent\Model;

/**
 * Class PendingTransition
 * @package Izoniks\LaravelEloquentMongodbStateMachines\Models
 * @property string $field
 * @property string $from
 * @property string $to
 * @property Carbon $transition_at
 * @property Carbon $applied_at
 * @property string $custom_properties
 * @property int $model_id
 * @property string $model_type
 * @property Model $model
 * @property int $responsible_id
 * @property string $responsible_type
 * @property Model $responsible
 */
class PendingTransition extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'pending_transitions';

    protected $guarded = [];

    protected $casts = [
        'transition_at' => 'datetime',
        'applied_at' => 'datetime',
        'custom_properties' => 'array',
    ];

    public function model()
    {
        return $this->morphTo();
    }

    public function responsible()
    {
        return $this->morphTo();
    }

    public function scopeNotApplied($query)
    {
        $query->whereNull('applied_at');
    }

    public function scopeOnScheduleOrOverdue($query)
    {
        $query->where('transition_at', '<=', now());
    }

    public function scopeForField($query, $field)
    {
        $query->where('field', $field);
    }

}
