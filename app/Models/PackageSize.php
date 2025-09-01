<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageSize extends Model
{
    protected $table = 'package_size';
    public $timestamps = false;
    protected $fillable = ['package_id', 'size_id'];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class , 'size_id');
    }
}
