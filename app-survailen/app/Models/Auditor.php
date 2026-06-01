<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditor extends Model
{
    protected $table = 'auditor';

    protected $primaryKey = 'id_auditor';

    protected $fillable = [
        'nama_auditor',
        'jabatan',
    ];
}
