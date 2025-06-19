<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GscQshaOh extends Model
{
    use HasFactory;

    protected $table = 'gsc_qsha_oh';

    protected $fillable = [
        'page_url',
        'total_impressions',
        'total_clicks',
        'avg_ctr',
        'avg_position',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'avg_ctr' => 'float',
        'avg_position' => 'float',
    ];
}
