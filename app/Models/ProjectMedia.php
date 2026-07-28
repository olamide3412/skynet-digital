<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMedia extends Model
{
    protected $table = 'project_media';

    protected $fillable = [
        'project_id',
        'type',
        'source_type',
        'file_path',
        'url',
        'order',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
