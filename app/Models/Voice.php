<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voice extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'question_id', 'value']; // Kolom yang dapat diisi secara massal

    protected $casts = [
        'user_id' => 'integer', // Mengubah kolom 'user_id' ke tipe data integer
        'question_id' => 'integer', // Mengubah kolom 'question_id' ke tipe data integer
        'value' => 'boolean', // Mengubah kolom 'value' ke tipe data boolean
        'created_at' => 'datetime', // Mengubah kolom 'created_at' ke tipe data datetime
        'updated_at' => 'datetime' // Mengubah kolom 'updated_at' ke tipe data datetime
    ];

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan model Question
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
