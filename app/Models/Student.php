<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'matricula',
        'is_active',
        'photo_path',
        'face_embedding',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'face_embedding' => 'array',
    ];

    protected $hidden = [
        'face_embedding',
    ];

    public function getPhotoUrlAttribute()
    {
        if ($this->photo_path) {
            if (Str::startsWith($this->photo_path, ['http://', 'https://'])) {
                return $this->photo_path;
            }
            return asset('storage/' . $this->photo_path);
        }
        return null;
    }
}
