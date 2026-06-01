<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $table = 'surat';

    protected $primaryKey = 'id_surat';

    protected $fillable = [
        'id_user',
        'id_surveillance',
        'jenis_surat',
        'tgl_terbit',
        'keterangan',
    ];

    public function surveilans()
    {
        return $this->belongsTo(Surveilans::class, 'id_surveillance', 'id_surveillance');
    }
}
