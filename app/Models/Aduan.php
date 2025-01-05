<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Aduan extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected $fillable = [
        'aduan_ict_tiket',
        'complainent_name',
        'complainent_id',
        'complainent_category',
        'aduan_category',
        'category',
        'aduan_subcategory',
        'campus',
        'location',
        'aduan_details',
        'aduan_status',
        'aduan_type',
        'staff_duty',
        'remark_staff_duty',
        'date_applied',
        'time_applied',
        'date_completed',
        'time_completed',
        'response_time',
        'rating'
    ];
}
