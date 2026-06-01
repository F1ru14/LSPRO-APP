<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    protected $table = 'perusahaan';

    protected $primaryKey = 'id_perusahaan';

    protected $fillable = [
        'id_kota',
        'nama_perusahaan',
        'alamat_kantor',
        'telp_kantor',
        'fax_kantor',
        'alamat_pabrik',
        'telp_pabrik',
        'fax_pabrik',
        'alamat_importir',
        'nama_importir',
        'telp_importir',
        'fax_importir',
        'email',
        'contact_person',
        'telp_cp',
        'merek',
        'komoditi',
        'tipe_produk',
    ];

    public function sertifikasi()
    {
        return $this->hasMany(Sertifikasi::class, 'id_perusahaan', 'id_perusahaan');
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota', 'id_kota');
    }
}
