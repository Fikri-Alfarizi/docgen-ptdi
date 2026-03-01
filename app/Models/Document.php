<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'user_id',
        'jenis_dokumen',
        'file_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
