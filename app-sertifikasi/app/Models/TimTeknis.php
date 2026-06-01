<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimTeknis extends Model
{
    protected $table = 'tim_teknis';

    protected $primaryKey = 'id_teknis';

    protected $fillable = ['nama_teknis'];
}
