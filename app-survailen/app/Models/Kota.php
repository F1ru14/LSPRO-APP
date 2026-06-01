<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    protected $table = 'kota';

    protected $primaryKey = 'id_kota';

    protected $fillable = [
        'id_provinsi',
        'kota',
    ];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'id_provinsi', 'id_provinsi');
    }

    public function perusahaan()
    {
        return $this->hasMany(Perusahaan::class, 'id_kota', 'id_kota');
    }
}
