<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\holiday;

class HolidayController extends Controller
{
    public function holiday($iso_3166, $year)
    {
        $holidayQuery = DB::table('holidays')->where('iso_3166', '=', $iso_3166)->get();
        if(count($holidayQuery) > 0)
        {
           return $holidayQuery;
        } else {
            return response()->json(['message' => 'No events for this country add new event.']);
        }
       
    }
    public function insertEvent($eventName,$eventDate,$eventType,$iso_3166)
    {  //update event from database
        $querystatus ='';
        try{
            DB::insert('insert into holidays (name, date, type, iso_3166, created_at, updated_at) values (?, ?, ?, ?, ?, ?)', [$eventName, $eventDate, $eventType, $iso_3166, now(), now()]);
            $querystatus = "Event added successfully";
        } catch(\Exception  $e)
        {
            $querystatus = "Error adding event";
        }
        return response()->json(["message" => $querystatus]);
    }
    public function updateEvent($eventId,$eventName,$eventDate,$eventType)
    {  //update event from database
        $querystatus ='';
        try{
            DB::update('update holidays set name =?, date =?, type =? where id =?', [$eventName, $eventDate, $eventType, $eventId]);
            $querystatus = "Event updated successfully";
        } catch(\Exception  $e)
        {
            $querystatus = "Error updating event";
        }
        return response()->json(["message" => $querystatus]);
    }
    public function deleteEvent($eventId){  //Delete event from database
        $querystatus ='';
        try{
            DB::delete('delete from holidays where id = ?', [$eventId]);
            $querystatus = "Event deleted successfully";
        } catch(\Exception  $e)
        {
            $querystatus = "Error deleting event";
        }
        return response()->json(["message" => $querystatus]);
    }
}
