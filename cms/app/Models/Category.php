<?php

namespace App\Models;

use App\Models\Concerns\HasLocaleTranslations;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasLocaleTranslations;

    protected $fillable = ['locale', 'translation_group_id', 'name', 'slug'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
