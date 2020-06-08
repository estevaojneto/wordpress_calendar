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

function queryEventsArchive($args){
    $result = new WP_Query( $args );
    return $result;
}

function fetchSimpleEvents()
{
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
    return queryEventsArchive($args_simple_events);
}

function fetchRecurrentEvents()
{
    $month_number = convertMonthFormat(get_query_var('monthnum'));
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
       'value'   => array(date('Y-m-d', '1899-01-01'), getArchiveEndDate($month_number)),
       'compare' => 'BETWEEN',
       'type'    => 'DATE'
     )
  )
);
    return queryEventsArchive($args_recurrent_event);
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
function printEventsGrid($eventsArray, $printingRecurrents)
{
    $events_counter = 0;
    if ( $eventsArray->have_posts() ) {	
        while ( $eventsArray->have_posts() ) {
            echo "<div class='bec-box-cell bec-event-card-border'>";
            echo $eventsArray->the_post();
            echo "<a href='".get_permalink(get_the_ID())."'> ".get_the_title()."</a>";
		    $eventDates = calcRecurringEventDates(get_post_meta(get_the_ID(), 'start_date', true), get_post_meta(get_the_ID(), 'recurrency', true), getArchiveStartDate(convertMonthFormat(get_query_var('monthnum'))));
            switch(get_post_meta(get_the_ID(), 'recurrency', true)){
                case 0:
                    echo '<p>';
                    _e('Start date:', 'becTextDomain');
                    echo "<br>";
                    echo get_post_meta(get_the_ID(), 'start_date', true)." @ ";
                    echo get_post_meta(get_the_ID(), 'start_time', true);
                    echo '</p>';
                    
                    echo '<p>';
                    _e('End date:', 'becTextDomain');
                    echo "<br>";
		            echo get_post_meta(get_the_ID(), 'end_date', true)." @ ";
		            echo get_post_meta(get_the_ID(), 'end_time', true);
		            echo '</p>';
		            echo "<input type='hidden' id='simple_start_date".$events_counter ."' value='".$eventDates[0]."'>";
                    echo "<input type='hidden' id='simple_event_name".$events_counter ."' value='".get_the_title() ."'>";
					echo "<input type='hidden' id='simple_event_url".$events_counter ."' value='".get_permalink(get_the_ID())."'>";
                    break;
                case 1:
                    echo '<p>';
                    _e('This is a daily event; it happens every day, from ', 'becTextDomain');
                    echo get_post_meta(get_the_ID(), 'start_time', true)." to ";
                    echo get_post_meta(get_the_ID(), 'end_time', true).".";
                    echo "<input type='hidden' id='recurrent_start_dates".$events_counter ."[]' value='".json_encode($eventDates)."'>";
                    echo "<input type='hidden' id='recurrent_event_name".$events_counter ."' value='".get_the_title() ."'>";
					echo "<input type='hidden' id='recurrent_event_url".$events_counter ."' value='".get_permalink(get_the_ID()) ."'>";
                    echo '</p>';
                    break;
                case 7:
                    echo '<p>';
                    _e('This is a weekly event; it is scheduled to happen next at: ', 'becTextDomain');
					echo $eventDates[0];
                    echo '</p>';
                    echo "<input type='hidden' id='recurrent_start_dates".$events_counter ."[]' value='".json_encode($eventDates)."'>";
                    echo "<input type='hidden' id='recurrent_event_name".$events_counter ."' value='".get_the_title() ."'>";
					echo "<input type='hidden' id='recurrent_event_url".$events_counter ."' value='".get_permalink(get_the_ID()) ."'>";
                    break;
                case 30:
                    echo '<p>';
                    _e('This is a monthly event; it is scheduled to happen next at: ', 'becTextDomain');
					echo $eventDates[0];
                    echo '</p>';
                    echo "<input type='hidden' id='recurrent_start_dates".$events_counter ."[]' value='".json_encode($eventDates)."'>";
                    echo "<input type='hidden' id='recurrent_event_name".$events_counter ."' value='".get_the_title() ."'>";
					echo "<input type='hidden' id='recurrent_event_url".$events_counter ."' value='".get_permalink(get_the_ID()) ."'>";
                    break;
                default:
                    break;
                    
            }
            echo '<p>';
            _e('Address: ', 'becTextDomain');
		    echo get_post_meta(get_the_ID(), 'address', true);
		    echo '</p>';
		            
		    echo '<p>';
            _e('Entry fee: ', 'becTextDomain');
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
    if($printingRecurrents)
        echo "<input type='hidden' id='qty_recurrent_events' value=$events_counter>";
    else
        echo "<input type='hidden' id='qty_simple_events' value=$events_counter>";
}
