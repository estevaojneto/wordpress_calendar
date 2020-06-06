<?php
get_header();
$i = 0;
echo "<input type='hidden' id='monthnum' value=".get_query_var('monthnum').">";
echo "<input type='hidden' id='day' value=".get_query_var('day').">";
echo "<input type='hidden' id='year' value=".get_query_var('year').">";

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
?>

<section class='container'>
	<div id='calendar' class='fullcalendar'>
	</div>
</section>
<section>

<?php if ( $the_query->have_posts() ) {	
    while ( $the_query->have_posts() ) {
        $the_query->the_post(); ?>
        <div> <a href='<?php echo get_permalink( get_the_ID() ); ?>'> <?php echo get_the_title(); ?></a>
		<p><?php echo get_post_meta( get_the_ID(), 'start_date', true )?></p>
		<input type='hidden' id='start_date<?php echo $i; ?>' value="<?php echo get_post_meta( get_the_ID(), 'start_date', true ); ?>">
		<input type='hidden' id='event_name<?php echo $i; ?>' value="<?php echo get_the_title(); ?>">
		<p><?php echo get_post_meta( get_the_ID(), 'end_date', true ); ?></p>
		</div>
		<?php $i++;
    }
} else {
    
} 
	echo "<input type='hidden' id='qty_events' value=$i>";
?>
</section>
<?php get_footer(); ?>