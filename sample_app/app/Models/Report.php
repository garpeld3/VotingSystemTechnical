<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Report extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'location',
        'latitude',
        'longitude',
        'priority',
        'status',
        'support_count',
        'image_path',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'support_count' => 'integer',
    ];

    protected $appends = ['image_url'];

    public function comments()
    {
        return $this->hasMany(\App\Models\ReportComment::class)->orderByDesc('created_at');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (blank($this->image_path)) {
            return null;
        }

        return Storage::disk('public')->url($this->image_path);
    }
}
