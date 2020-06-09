<?php

/**This file contains all the functions we need to build the archive-event page.
 * In the event any of those functions end up being useful in more than only this
 * page, I shall move such function to 'helpers.php' (it is literally why it exists).
 */ 

function initArchiveEvent()
{
    catchEmptyArchive();
    return;
}

function getArchiveStartDate($month_number)
{
    return date("Y-m-d", strtotime(get_query_var('year').'-'.$month_number.'-01'));
}

function getArchiveEndDate($month_number)
{
    return date("Y-m-t", strtotime(getArchiveStartDate($month_number)));
}

function getStartDate($startDate)
{
	return $startDate[0];
}

function getEndDate($startDate, $originalStart, $originalEnd)
{
	$eventLength = calcEventLengthInDays($originalStart, $originalEnd, true);
	return calcEventEndDate($startDate[0], $eventLength);
}

function fetchEvents(){
	$month_number = convertMonthFormat(get_query_var('monthnum'));
	$args_simple_events = array(
	'post_type' => 'event',
	'meta_key' => 'start_date', 
	'meta_value'     => array(getArchiveStartDate($month_number), getArchiveEndDate($month_number)),
    'meta_compare' => 'BETWEEN', 
	'type' => 'DATE',
	'meta_query'     => array(
        'relation'  => 'AND',
         array (
            'key'     => 'recurrency',
            'value'   => '0',
            'compare' => '=',
            'type'    => 'NUMERIC'
        )
     )
    );
	$args_recurrent_event = array(
  'post_type' => 'event',
  'meta_key' => 'recurrency',
  'meta_value'   => "0",
  'meta_type'      => 'NUMERIC',
  'meta_compare'   => '>',
  'orderby'        => 'meta_value',
  'order'          => 'DESC',
  'meta_query'     => array(
    'relation'  => 'AND',
     array (
       'key'     => 'start_date',
       'value'   => getArchiveEndDate($month_number),
       'compare' => '<',
       'type'    => 'DATE'
     )
  )
);
	$query1 = new WP_Query($args_simple_events); //fetches this month's single (one-time) events
	$query2 = new WP_Query($args_recurrent_event); //fetches recurrent events that are happening this month
	$wp_query = new WP_Query();
	//Let me justify here saying the following: it was either this or
	//A) Breaking the DRY principle not only here in PHP but also on the JS for loading calendar
	//B) SQL Querying the Wordpress database directly
	//array_merging two objects belonging to the same class is far less worse than creating
	//unmaintainable code (A) or outright undertaking a security risk (B)
	$wp_query->posts = array_merge( $query1->posts, $query2->posts );
	$wp_query->post_count = $query1->post_count + $query2->post_count;
    return $wp_query;
}

function convertMonthFormat($wp_month)
{
    return sprintf('%02d', get_query_var('monthnum')); // converts to a friendly month format we can use in a query
}

function catchEmptyArchive()
{
    if(get_query_var('monthnum') == 0)
        set_query_var('monthnum', date('m'));
    if(get_query_var('year') == 0)
        set_query_var('year', date('Y'));
    return;
}

/**The signature seems peculiar, doesn't it? Why do we need to know what we're printing?
 * This is to avoid the following bug: if a month has recurring events but no simple 
 * event, then qty_simple_events is never echoed, and Javascript will attempt
 * to access an inexistant document ID; the script will then crash, and will also
 * fail to add our neat recurring events to FullCalendar.
 */
function printEventsGrid($eventsArray)
{
    $events_counter = 0;
    if ( $eventsArray->have_posts() ) {	
        while ( $eventsArray->have_posts() ) {
            echo "<div class='bec-box-cell bec-event-card-border'>";
            echo $eventsArray->the_post();
            echo "<a href='".get_permalink(get_the_ID())."'> ".get_the_title()."</a>";
		    $eventDates = calcRecurringEventDates(get_post_meta(get_the_ID(), 'start_date', true), get_post_meta(get_the_ID(), 'recurrency', true), getArchiveStartDate(convertMonthFormat(get_query_var('monthnum'))));
             echo '<p>';
			_e('Start date:', 'becTextDomain');
			echo getStartDate($eventDates);
			echo " @ ";
			echo get_post_meta(get_the_ID(), 'start_time', true);
			echo "</p>";
                    
            echo '<p>';
            _e('End date:', 'becTextDomain');
			echo getEndDate($eventDates, get_post_meta(get_the_ID(), 'start_date', true), get_post_meta(get_the_ID(), 'end_date', true));
			echo " @ ";
		    echo get_post_meta(get_the_ID(), 'end_time', true);
			echo "</p>";
			
		    echo "<input type='hidden' id='start_dates".$events_counter ."[]' value='".json_encode($eventDates)."'>";
            echo "<input type='hidden' id='event_name".$events_counter ."' value='".get_the_title() ."'>";
			echo "<input type='hidden' id='event_url".$events_counter ."' value='".get_permalink(get_the_ID())."'>";
			echo '</p>';
            echo '<p>';
            _e('Address:', 'becTextDomain');
            echo " ";
		    echo get_post_meta(get_the_ID(), 'address', true);
		    echo '</p>';
		            
		    echo '<p>';
            _e('Entry fee:', 'becTextDomain');
            echo " ";
			echo "$";
		    echo get_post_meta(get_the_ID(), 'price', true);
		    echo '</p>';
		    echo "</div>";
            ++$events_counter;
        }
    }
    else{
        _e('No events found', 'becTextDomain');
    }
        echo "<input type='hidden' id='qty_events' value=$events_counter>";
}
