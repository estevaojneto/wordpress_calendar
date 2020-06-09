<?php
require_once 'builder.php';
initArchiveEvent();
get_header();
?>

<article>
<section>
    <div class='bec-container'>
    <input type='hidden' id='monthnum' value="<?php echo get_query_var('monthnum');?>">
    <input type='hidden' id='day' value="<?php echo get_query_var('day');?>">
    <input type='hidden' id='year' value="<?php echo get_query_var('year');?>">

    <div id='calendar' class='bec-fullcalendar'>
    </div>
    </div>
</section>

<section>
    <div class='bec-container'>
    <h2 class="widget-title">
        <?php
        $queriedMonthYear = date('Y-m', strtotime(get_query_var('year')."-".get_query_var('monthnum')));
        if ($queriedMonthYear == date('Y-m')) {
            _e('All events this month', 'becTextDomain');
        } elseif ($queriedMonthYear < date('Y-m')) {
            _e('Archived events', 'becTextDomain');
        } else {
            _e('Future events', 'becTextDomain');
        }
        ?>
    </h2>
<div class="bec-box">
<div class="bec-box-row">
<?php
    printEventsGrid(fetchEvents());
?>
</div>
</div>
</div>
</section>


<section>
<div class='bec-container'>
    <h2 class="widget-title">
        <?php _e('Navigate archive', 'becTextDomain'); ?>
    </h2>
    <form id="archiveNavButton">
        <select required name="year" id="year">
            <option value="" selected></option>
            <option value=2018>2018</option>
            <option value=2019>2019</option>
            <option value=2020>2020</option>
        </select>
        <select required name="monthnum" id="monthnum">
            <option value="" selected></option>
            <option value="01">01</option>
            <option value="02">02</option>
            <option value="03">03</option>
            <option value="04">04</option>
            <option value="05">05</option>
            <option value="06">06</option>
            <option value="07">07</option>
            <option value="08">08</option>
            <option value="09">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
        </select>
        <input type=hidden name="post_type" value="event">
        <button><?php _e('Navigate', 'becTextDomain'); ?></button>
    </form>
</div>
</section>
</article>
<?php get_footer(); ?>