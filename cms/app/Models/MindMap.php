<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MindMap extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'data',
    ];

    public static function defaultData(string $topic = 'New Mind Map'): array
    {
        return [
            'nodeData' => [
                'id' => 'root',
                'topic' => $topic,
                'children' => [],
            ],
        ];
    }

    public static function defaultJson(string $topic = 'New Mind Map'): string
    {
        return json_encode(
            static::defaultData($topic),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    public function decodedData(): array
    {
        $decoded = json_decode($this->data ?? '', true);

        return is_array($decoded) && isset($decoded['nodeData'])
            ? $decoded
            : static::defaultData($this->title ?: 'New Mind Map');
    }
}
