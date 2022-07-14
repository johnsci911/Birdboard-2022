<?php

namespace App;

trait RecordsActivity
{
    public $oldAttribute = [];

    public static function bootRecordsActivity()
    {
        static::updating(function ($model) {
            $model->oldAttribute = $model->getOriginal();
        });


    }

    public function recordActivity($description)
    {
        $this->activity()->create([
            'description' => $description,
            'changes' => $this->activityChanges(),
            'project_id' => class_basename($this) === 'Project' ? $this->id : $this->project_id
        ]);
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    public function activityChanges()
    {
        if ($this->wasChanged()) {
            return [
                'before' => array_diff($this->oldAttribute, array_except($this->getAttributes(), 'updated_at')),
                'after' => array_except($this->getChanges(), 'updated_at')
            ];
        }
    }
}
