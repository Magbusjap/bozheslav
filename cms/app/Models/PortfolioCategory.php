<?php

namespace App\Models;

use App\Models\Concerns\HasLocaleTranslations;
use Illuminate\Database\Eloquent\Model;

class PortfolioCategory extends Model
{
    use HasLocaleTranslations;

    protected $fillable = ['locale', 'translation_group_id', 'name', 'slug', 'sort_order', 'status'];

    public function projects()
    {
        return $this->hasMany(PortfolioProject::class);
    }
}
