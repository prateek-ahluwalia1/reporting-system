<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityManagement extends Model
{
    use HasFactory;

    protected $table = 'security_management';

    protected $fillable = [
        'costCentres',
        'countries',
        'customerGroups',
        'mode',
        'name',
        'departments',
        'email',
        'menuAccess',
        'menuSecurityEnabled',
        'productGroups',
        'regions',
        'reportAccess',
        'reportSecurityEnabled',
        'salesReps',
        'states',
        'overrides'
    ];

    protected $casts = [
        'costCentres' => 'array',
        'countries' => 'array',
        'customerGroups' => 'array',
        'departments' => 'array',
        'menuAccess' => 'array',
        'productGroups' => 'array',
        'regions' => 'array',
        'reportAccess' => 'array',
        'salesReps' => 'array',
        'states' => 'array',
        'overrides' => 'array',
        'menuSecurityEnabled' => 'boolean',
        'reportSecurityEnabled' => 'boolean',
    ];
}