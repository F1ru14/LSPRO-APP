<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetugasPengambilContoh extends Model
{
    protected $table = 'petugas_pengambil_contoh';

    protected $primaryKey = 'id_ppc';

    protected $fillable = ['nama_ppc', 'jabatan'];
}
