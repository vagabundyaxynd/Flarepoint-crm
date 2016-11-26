<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tasks;

class SearchController extends Controller
{
    public function index($query)
    {
    	$results =  Tasks::search($query)->paginate(10);

    	return view('search.index')
    	->withResults($results);
    }
}
