<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\SchedulerMainController;
use App\Models\Scheduler\EventModel;

class SchedulerDashboardController extends SchedulerMainController {
   

	public function index()
	{

		$events[] = \Calendar::event(
		    "Monthsarry si KEN",
		    false,
		    '2015-07-14T12:00:00', 
		    '2015-07-14T13:00:00',
			1
		);

		$events[] = \Calendar::event(
		    "wala lang",
		    false,
		    '2015-07-16T13:00:00', 
		    '2015-07-16T13:15:00',
			1
		);

        $eloquentEvent = EventModel::first(); //EventModel implements MaddHatter\LaravelFullcalendar\Event  

        $calendar = \Calendar::addEvents($events, [ //set custom color fo this event
    			    'color' => '#34534',
    			])
				->setOptions([ //set fullcalendar options
			        'firstDay' => 1
			    ])
			    ->setCallbacks([ //set fullcalendar callback options (will not be JSON encoded)
			        // 'viewRender' => 'function() {alert("Callbacks!");}'
			    ]); 

       return view('scheduler/index',  compact('calendar'));
       // return view('scheduler_dashboard/dashboard');
	}
	public function scheduler()
	{
		return view('scheduler/create');
	}

}   

    