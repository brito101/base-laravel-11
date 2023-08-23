<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ChangelogController extends Controller
{
    public function index()
    {
        return view('admin.changelog.index');
    }
}
