<?php

namespace Alauddin\Authorize\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id', 'namespace', 'controller', 'method', 'action', 'allowed',
    ];


    /**
     * Get the type of the user.
     */
    public function role()
    {
        return $this->belongsTo('Alauddin\Authorize\Models\Role');
    }
}
