<?php

/**This file has functions which can be reused and/or they are too complex to simply
 * be put on other files which are already doing other functions - I especially 
 * want the template controllers to remain clean and comply with SRP.
 */ 


/** I believe this is where our skills as candidates are truly tested. Recurrency
 * can be done in many ways, but I don't think cluttering our database with events
 * is the way to go. So I decided to create virtual event dates
 * 
 * Here's how this works: $originalDate (date) is the original date of a certain event,
 * $recurrency (integer) is how often it happens again (daily, weekly or monthly), and 
 * $currentDate (date) is actually the first day of the month we're interested
 * in knowing when the event will be. 
 * For example: if an event happens every Friday, what will be its dates in June 2020? 
 * This functions calculates and answers that.
 * It returns an array with all the dates the event will happen in a given month.
 */

function calcRecurringEventDates($originalDate, $recurrency, $currentDate)
{
    $eventDates = [];
    switch($recurrency){
        case 0:
            array_push($eventDates, $originalDate);
            break;
        case 1: // daily event
            $originalDate = date('Y-m-d', strtotime($originalDate));
            $currentDate = date('Y-m-d', strtotime($currentDate));
            if (!$originalDate || !$currentDate)
                return false;
            for($i = $currentDate; $i <= date("Y-m-t", strtotime($currentDate)); $i++)
                array_push($eventDates, date("Y-m-d", strtotime($i)));
            break;
        case 7: // weekly event
            $originalWeekday = date('w', strtotime($originalDate));
            for($i = $currentDate; $i <= date("Y-m-t", strtotime($currentDate)); $i++)
                if(date('w', strtotime($i)) == $originalWeekday)
                    array_push($eventDates, date("Y-m-d", strtotime($i)));
            break;
        case 30: // monthly event
            $originalDay = date("d", strtotime($originalDate));
            $currentMonthYear = date("Y-m", strtotime($currentDate));
            if (!$originalDay || !$currentMonthYear)
                return false;
            $resultingDate = date("Y-m-d", strtotime($currentMonthYear."-".$originalDay));			
            array_push($eventDates, $resultingDate);
			break;
        default:
            return false; // unexpected recurrency (weird value); returns false
            break;
    }
    return $eventDates;
}

function calcClosestEventDate($eventDates)
{
	if(!is_array($eventDates)) // we're expect an array, more precisely one created by calcRecurringEventDates()
		return false;
	// TODO rest of logic
}