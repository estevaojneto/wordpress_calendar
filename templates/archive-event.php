<?php

/**There is no year 0 (PHP defaults to 1899) and no month 0 (PHP defaults to January);
 * if either is 0, then something is wrong, and we should treat it properly.
 * This defaults the archive view to the current month and year to avoid that weirdness.
 */
require( BEC_PLUGIN_PATH . 'helpers.php');

if(get_query_var('monthnum') == 0 || get_query_var('year') == 0){
	set_query_var('monthnum', date('m'));
	set_query_var('year', date('Y'));
}
set_query_var('monthnum', "12");
$events_counter = 0; // we'll need to count how many events we're showing in this page; I will explain it later.
$month_from_wp = get_query_var('monthnum');
$month_number = sprintf('%02d', $month_from_wp); // converts to a friendly month format we can use in a query

$start_date = date(get_query_var('year').'-'.$month_number.'-01');
$end_date = date("Y-m-t", strtotime($start_date));
$args = array(
	'post_type' => 'event',
	'meta_key' => 'start_date', 
	'meta_value'     => array($start_date, $end_date),
    'meta_compare' => 'BETWEEN', 
	'type' => 'DATE'
);
$the_query = new WP_Query( $args );


$args_recurrent = array(
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
       'value'   => array(date('Y-m-d', '1899-01-01'), $end_date),
       'compare' => 'BETWEEN',
       'type'    => 'DATE'
     )
  )
);

$the_query_recurrent = new WP_Query( $args_recurrent );

get_header();
?>

<section class='container'>
	<input type='hidden' id='monthnum' value="<?php echo get_query_var('monthnum');?>">
	<input type='hidden' id='day' value="<?php echo get_query_var('day');?>">
	<input type='hidden' id='year' value="<?php echo get_query_var('year');?>">

	<div id='calendar' class='fullcalendar' style="width:80%;zoom:60%;margin: 0 auto">
	</div>
</section>
<section>

<?php 
	
/**
 * Before we go any further, I must explain the logic behind this next loop.
 * "archive-event" holds a monthly archive (as per specification 4.2), with a
 * calendar jQuery plugin (FullCalendar). We'll need to pass information to this *JS*
 * plugin so it may insert the events that we will fetch with *PHP* using WP_QUERY;
 * one simple way of sending this PHP data to JS is is saving the necessary information
 * in invisible inputs identified with certain IDs, and loading this information with 
 * document.getElementById().value; if you wanna see how this is done on the JS side,
 * you can look at scripts/load_calendar.js. So one crucial thing we'll do on the next
 * "while" loop is create those inputs from where JS will fill our calendar neatly.
 * This is also why we need $events_counter, so we can also save it in a input so
 * our JS knows how many events it should add.
 * 
 * If we were doing something more complex and elaborate, maybe using data-attribs
 * would be more proper, but this works fine for this simple plugin.
 */
	if ( $the_query->have_posts() ) {	
    while ( $the_query->have_posts() ) {
        $the_query->the_post(); ?> 
        <div> <a href='<?php echo get_permalink( get_the_ID() ); ?>'> <?php echo get_the_title(); ?></a>
		<p><?php echo get_post_meta( get_the_ID(), 'start_date', true )?></p>
		<input type='hidden' id='start_date<?php echo $events_counter; ?>' value="<?php echo get_post_meta( get_the_ID(), 'start_date', true ); ?>">
		<input type='hidden' id='event_name<?php echo $events_counter; ?>' value="<?php echo get_the_title(); ?>">
		<p><?php echo get_post_meta( get_the_ID(), 'end_date', true ); ?></p>
		</div>
		<?php ++$events_counter;
    }
} else {
    ?> <p>No events to show i18n</p>; <?php
} 
	echo "<p>Recurrent events i18n:</p>";
	if ( $the_query_recurrent->have_posts() ) {
    while ( $the_query_recurrent->have_posts() ) {
        $the_query_recurrent->the_post(); ?> 
        <div> <a href='<?php echo get_permalink( get_the_ID() ); ?>'> <?php echo get_the_title(); ?></a>
		<p>
			Event dates are:
			</p>
		<?php 
		$event_dates = calcRecurringEventDates(get_post_meta( get_the_ID(), 'start_date', true ), get_post_meta( get_the_ID(), 'recurrency', true ), $start_date);
		foreach($event_dates as $date){
			echo $date."<br>";
		}
		?>
		</div>
		<?php ++$events_counter;
    }
} else {
    ?> <p>No recurrent events to show i18n</p>; <?php
	}
	echo "<input type='hidden' id='qty_events' value=$events_counter>";
?>
</section>
<?php get_footer(); ?>