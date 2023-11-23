<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:dashboard-read', ['only' => ['index']]);
    }

    public function index()
    {
        return view('dashboards');
    }
}
