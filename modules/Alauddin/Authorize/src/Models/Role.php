<?php

namespace Alauddin\Authorize\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'alias',
    ];


    /**
     * Get the users for the role.
     */
    public function users()
    {
        return $this->hasMany(Config("authorization.user-model"));
    }

    /**
     * Get the users for the role.
     */
    public function permissions()
    {
        return $this->hasMany('Alauddin\Authorize\Models\Permission');
    }
}
