<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\country;
use Illuminate\Support\Facades\DB;

class CountryController extends Controller
{
    public function countryList()
    {
        $country = DB::table('countries')->get();
        return view('holidayCalendar', compact('country'));
    }
}
