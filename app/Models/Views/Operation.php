<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    protected $table = 'operations_view';

    protected $appends = [
        'start_pt',
        'end_pt',
    ];

    public function getStartPtAttribute($value)
    {
        if ($this->start) {
            return date('d/m/Y H:i', strtotime($this->start));
        } else {
            return null;
        }
    }

    public function getEndPtAttribute($value)
    {
        if ($this->end) {
            return date('d/m/Y H:i', strtotime($this->end));
        } else {
            return null;
        }
    }
}
