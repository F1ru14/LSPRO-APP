<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surveilans extends Model
{
    protected $table = 'surveillance';

    protected $primaryKey = 'id_surveillance';

    protected $fillable = [
        'id_user',
        'id_sertifikasi',
        'id_lab',
        'periode',
        'tgl_pelaksanaan',
        'keterangan',
    ];

    public function sertifikasi()
    {
        return $this->belongsTo(Sertifikasi::class, 'id_sertifikasi', 'id_sertifikasi');
    }

    public function surats()
    {
        return $this->hasMany(Surat::class, 'id_surveillance', 'id_surveillance');
    }

    public function auditors()
    {
        return $this->belongsToMany(Auditor::class, 'surveillance_auditor', 'id_surveillance', 'id_auditor');
    }

    public function petugasPengambilContoh()
    {
        return $this->belongsToMany(PetugasPengambilContoh::class, 'surveillance_petugas_pengambil_contoh', 'id_surveillance', 'id_ppc');
    }

    public function lab()
    {
        return $this->belongsTo(Lab::class, 'id_lab', 'id_lab');
    }
}
