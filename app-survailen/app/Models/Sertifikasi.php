<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sertifikasi extends Model
{
    protected $table = 'sertifikasi';

    protected $primaryKey = 'id_sertifikasi';

    protected $fillable = [
        'id_surat',
        'id_perusahaan',
        'id_lab',
        'no_referensi',
        'id_kategori',
        'no_sni',
        'tgl_permohonan',
        'tgl_kontrak',
        'tgl_audit_kecukupan',
        'tgl_pemberitahuan_verifikasi',
        'tgl_mulai_audit_lapangan',
        'tgl_selesai_audit_lapangan',
        'tgl_rapat_teknis',
        'tgl_sertifikasi',
        'lama_sertifikasi',
        'status_permohonan',
        'keterangan',
    ];

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan', 'id_perusahaan');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function lab()
    {
        return $this->belongsTo(Lab::class, 'id_lab', 'id_lab');
    }

    public function auditors()
    {
        return $this->belongsToMany(Auditor::class, 'sertifikasi_auditor', 'id_sertifikasi', 'id_auditor')->withPivot('peran');
    }

    public function petugasPengambilContoh()
    {
        return $this->belongsToMany(PetugasPengambilContoh::class, 'sertifikasi_petugas_pengambil_contoh', 'id_sertifikasi', 'id_ppc');
    }

    public function timTeknis()
    {
        return $this->belongsToMany(TimTeknis::class, 'sertifikasi_tim_teknis', 'id_sertifikasi', 'id_teknis');
    }

    public function surveilans()
    {
        return $this->hasMany(Surveilans::class, 'id_sertifikasi', 'id_sertifikasi');
    }

    public function getNamaPerusahaanAttribute()
    {
        return $this->perusahaan->nama_perusahaan ?? null;
    }

    public function getNamaAuditorAttribute()
    {
        return $this->auditors->where('pivot.peran', 'lead')->pluck('nama_auditor')->implode(',');
    }

    public function getAuditorKecukupanAttribute()
    {
        return $this->auditors->where('pivot.peran', 'kecukupan')->pluck('nama_auditor')->implode(',');
    }

    public function getNamaPetugasAttribute()
    {
        return $this->petugasPengambilContoh->pluck('nama_ppc')->implode(',');
    }

    public function getNamaTeknisAttribute()
    {
        return $this->timTeknis->pluck('nama_teknis')->implode(',');
    }

    public function getNamaLabAttribute()
    {
        return $this->lab->nama_lab ?? null;
    }

    public function getEmailAttribute()
    {
        return $this->perusahaan->email ?? null;
    }

    public function getKomoditiProdukAttribute()
    {
        return $this->perusahaan->komoditi ?? null;
    }

    public function getMerkAttribute()
    {
        return $this->perusahaan->merek ?? null;
    }

    public function getAlamatKantorAttribute()
    {
        return $this->perusahaan->alamat_kantor ?? null;
    }

    public function getTelpKantorAttribute()
    {
        return $this->perusahaan->telp_kantor ?? null;
    }

    public function getFaxKantorAttribute()
    {
        return $this->perusahaan->fax_kantor ?? null;
    }

    public function getAlamatPabrikAttribute()
    {
        return $this->perusahaan->alamat_pabrik ?? null;
    }

    public function getTelpPabrikAttribute()
    {
        return $this->perusahaan->telp_pabrik ?? null;
    }

    public function getFaxPabrikAttribute()
    {
        return $this->perusahaan->fax_pabrik ?? null;
    }

    public function getAlamatImportirAttribute()
    {
        return $this->perusahaan->alamat_importir ?? null;
    }

    public function getTelpImportirAttribute()
    {
        return $this->perusahaan->telp_importir ?? null;
    }

    public function getFaxImportirAttribute()
    {
        return $this->perusahaan->fax_importir ?? null;
    }

    public function getKontakPersonAttribute()
    {
        return $this->perusahaan->contact_person ?? null;
    }

    public function getTelpCpAttribute()
    {
        return $this->perusahaan->telp_cp ?? null;
    }

    public function getTypeJenisProdukAttribute()
    {
        return $this->perusahaan->tipe_produk ?? null;
    }
}
