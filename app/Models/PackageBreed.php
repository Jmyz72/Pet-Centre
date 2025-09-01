<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageBreed extends Model
{
    protected $table = 'package_breed';
    public $timestamps = false;
    protected $fillable = ['package_id', 'breed_id'];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function breed()
    {
        return $this->belongsTo(PetBreed::class, 'breed_id');
    }
}
