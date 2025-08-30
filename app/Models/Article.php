<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;
    protected $fillable = [
        'title','slug','summary','content','documentation','author',
        'hashtags','thumbnail','status'
    ];

    // Cast ke array saat diakses
    protected $casts = [
        'documentation' => 'array',
    ];

    // Buat slug otomatis jika kosong
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = static::uniqueSlug($model->title);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('title')) {
                $model->slug = static::uniqueSlug($model->title, $model->id);
            }
        });
    }

    public static function uniqueSlug(string $title, $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $base = $slug;
        $i = 1;
        while (static::where('slug', $slug)->when($ignoreId, fn($q)=>$q->where('id','!=',$ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }
        return $slug;
    }

    public function scopePublished($q)
    {
        return $q->where('status','published');
    }

    public function getHashtagArrayAttribute(): array
    {
        return collect(explode(',', (string)$this->hashtags))
            ->map(fn($t)=>trim($t))
            ->filter()
            ->values()
            ->all();
    }
}
