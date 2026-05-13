<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    // Добавляем эти поля, чтобы Laravel разрешил их сохранять
    protected $fillable = [
        'title',
        'subject',
        'mjml_content',
    ];
}