<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id']; // Kolom yang dapat diisi secara massal

    protected $casts = [
        'user_id' => 'integer', // Mengubah kolom 'user_id' ke tipe data integer
        'created_at' => 'datetime', // Mengubah kolom 'created_at' ke tipe data datetime
        'updated_at' => 'datetime' // Mengubah kolom 'updated_at' ke tipe data datetime
    ];

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan model Voice
    public function voice()
    {
        return $this->hasMany(Voice::class);
    }
}
