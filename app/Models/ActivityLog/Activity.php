<?php
namespace App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Casts\Attribute; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model
{
    protected $table = 'activity_log';

    public function causer(): MorphTo { return $this->morphTo(); }
    public function subject(): MorphTo { return $this->morphTo(); }

    public function causerName(): Attribute
    {
        return Attribute::get(fn () => $this->causer?->name ?? 'System/Guest');
    }

    public function causerEmail(): Attribute
    {
        return Attribute::get(fn () => $this->causer?->email ?? 'N/A');
    }

    public function subjectDescription(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->subject) {
                return 'System';
            }

            $resourceType = class_basename($this->subject_type);
            return "{$resourceType} #{$this->subject_id}";
        });
    }
}
