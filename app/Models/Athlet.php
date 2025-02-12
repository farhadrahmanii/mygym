<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Athlet extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'father_name',
        'phone_number',
        'photo',
        'fee_id',
        'admission_type',
        'box_id',
        'details',
    ];

    public function box()
    {
        return $this->belongsTo(Box::class, 'box_id');
    }
    public function fees()
    {
        return $this->belongsTo(Fee::class, 'fee_id');
    }
}
