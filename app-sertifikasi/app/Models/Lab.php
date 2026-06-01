<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    use HasFactory;

    protected $table = 'lab';

    protected $primaryKey = 'id_lab';

    public $timestamps = false;

    protected $fillable = [
        'nama_lab',
        'keterangan',
    ];
}
