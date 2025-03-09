<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitYesterday extends Model
{
    use HasFactory;

    protected $table = 'visitors_yesterday_view';
}
