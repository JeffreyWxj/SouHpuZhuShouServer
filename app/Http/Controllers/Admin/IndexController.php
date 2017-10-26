<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;

class IndexController extends Controller
{
  public function index()
  {
    $user_count = Student::count();
    return view('admin.index.index', compact('user_count'));
  }
}
