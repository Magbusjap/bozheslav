<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trash extends Model
{
    protected $table = 'trash';

    protected $fillable = [
        'model_type',
        'model_label',
        'model_name',
        'model_data',
        'original_id',
        'deleted_by',
        'expires_at',
    ];

    protected $casts = [
        'model_data' => 'array',
        'expires_at' => 'datetime',
    ];

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    // Move any model to trash
    public static function moveToTrash(Model $model, string $label, string $nameField = 'title'): self
    {
        return static::create([
            'model_type'  => get_class($model),
            'model_label' => $label,
            'model_name'  => $model->{$nameField} ?? $model->id,
            'model_data'  => $model->toArray(),
            'original_id' => $model->id,
            'deleted_by'  => auth()->id(),
            'expires_at'  => now()->addDays(60),
        ]);
    }

    // Restore model from trash
    public function restore(): bool
    {
        $modelClass = $this->model_type;
        if (!class_exists($modelClass)) return false;

        $data = $this->model_data;
        unset($data['id']);

        $modelClass::create($data);
        $this->delete();

        return true;
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function daysLeft(): int
    {
        return max(0, now()->diffInDays($this->expires_at, false));
    }
}