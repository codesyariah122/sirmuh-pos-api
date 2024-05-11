<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisPemasukan extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = "jenis_pemasukan";
}
