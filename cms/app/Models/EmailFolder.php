<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmailFolder extends Model
{
    protected $table = 'email_folders';

    protected $fillable = ['name', 'slug'];

    protected static function booted(): void
    {
        static::creating(function ($folder) {
            $folder->slug = Str::slug($folder->name);
        });

        static::updating(function ($folder) {
            $folder->slug = Str::slug($folder->name);
        });
    }

    public function media()
    {
        return $this->hasMany(EmailMedia::class, 'folder', 'slug');
    }
}