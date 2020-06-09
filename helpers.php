<?php

/**
 * This file has functions which can be reused and/or they are too complex to simply
 * be put on other files which are already doing other functions - I especially 
 * want the template controllers to remain clean and comply with SRP.
 */ 


/**
 * I believe this is where our skills as candidates are truly tested. Recurrency
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
        if (!$originalDate || !$currentDate) {
            return false;
        }
        for($i = $currentDate; $i <= date("Y-m-t", strtotime($currentDate)); $i++) {
            array_push($eventDates, date("Y-m-d", strtotime($i)));
        }
        break;
    case 7: // weekly event
        $originalWeekday = date('w', strtotime($originalDate));
        for($i = $currentDate; $i <= date("Y-m-t", strtotime($currentDate)); $i++) {
            if(date('w', strtotime($i)) == $originalWeekday) {
                array_push($eventDates, date("Y-m-d", strtotime($i)));
            }
        }
        break;
    case 30: // monthly event
        $originalDay = date("d", strtotime($originalDate));
        $currentMonthYear = date("Y-m", strtotime($currentDate));
        if (!$originalDay || !$currentMonthYear) {
            return false;
        }
        $resultingDate = date("Y-m-d", strtotime($currentMonthYear."-".$originalDay));            
        array_push($eventDates, $resultingDate);
        break;
    default:
        return false; // unexpected recurrency (weird value); returns false
            break;
    }
    //array_filters the dates to not have any dates in the past (as per second bullet of requirement 4.2);
    //array_values ensures the very next event date will be at the first position of the array
    $filteredDates = array_values(
        array_filter(
            $eventDates, function ($date) {
                return($date >= date('Y-m-d')); 
            }
        )
    );    
    return $filteredDates;
}

function calcEventLengthInDays($startDate, $endDate)
{
    $deltaDate = strtotime($endDate) - strtotime($startDate);
    return round($deltaDate / (60 * 60 * 24));
}


/* You'll probably want to call calcEventLengthInDays() before this to find the length in days of an event.
 * Notice how I'm not calling it "calcRecurringEventEndDate"; this means this function can be applied to
 * simple, one-time events too, if it ever proves necessary. */
function calcEventEndDate($startDate, $eventLengthInDays)
{
    $date = date_create($startDate);
    date_add($date, date_interval_create_from_date_string($eventLengthInDays." days"));
    return date_format($date, "Y-m-d");
}