<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportManagement extends Model
{
    use HasFactory;

    protected $table = 'report_managements';

    protected $fillable = [
        'name',
        'description',
        'parent_menu',
        'child_menu',
        'grandchild_menu',
        'category',
        'grand_child_category',
        'sub_category',
        'published',
        'stored_procedure',
        'viewer_type',
        'parameters',
        'report_file'
    ];

    protected $casts = [
        'published' => 'boolean',
        'parameters' => 'array'
    ];

    // Accessor to decode parameters if needed
    public function getParametersAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    // Mutator to encode parameters
    public function setParametersAttribute($value)
    {
        $this->attributes['parameters'] = $value ? json_encode($value) : null;
    }
}