<?php
echo "Events for month:".get_query_var('monthnum');
$month_from_wp = get_query_var('monthnum');
$month_number = sprintf('%02d', $month_from_wp);

$start_date = date(get_query_var('year').'-'.$month_number.'-01'); // First day of the month
$end_date = date("Y-m-t", strtotime($start_date));

$args = array(
	'post_type' => 'event',
	'meta_key' => 'start_date', 
	'meta_value'     => array($start_date, $end_date),
    'meta_compare' => 'BETWEEN', 
	'type' => 'DATE'
);

$the_query = new WP_Query( $args );
// The Loop
if ( $the_query->have_posts() ) {
    echo '<ul>';
    while ( $the_query->have_posts() ) {
        $the_query->the_post();
        echo '<li> <a href="'.get_permalink( get_the_ID() ).'">' . get_the_title() . '</a></li>';
    }
    echo '</ul>';
} else {
    // no posts found
}
?>