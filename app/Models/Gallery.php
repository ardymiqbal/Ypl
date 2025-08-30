<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gallery extends Model
{
    use HasFactory;
    protected $fillable = [
        'title','slug','description','media_type','media_path','is_published'
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($m) {
            if (empty($m->slug)) {
                $m->slug = static::uniqueSlug($m->title);
            }
        });

        static::updating(function ($m) {
            if ($m->isDirty('title')) {
                $m->slug = static::uniqueSlug($m->title, $m->id);
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
}
