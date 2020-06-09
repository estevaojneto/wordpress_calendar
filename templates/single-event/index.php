<?php 
require_once 'builder.php';
get_header(); 
?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<article>
<section>
<div class='bec-container'>
<div class="post" id="post-<?php the_ID(); ?>">    
    <h2 class="widget-title"><?php the_title(); ?></h2>
    <div class="bec-box bec-event-card-border">
    <div class="bec-box-row">    
        <?php printFeaturedImageIfExists(); ?>
    <div class='bec-box-cell'>        
        <h3><?php _e('Event info', 'becTextDomain');?></h3>
        <p><?php printRecurrencyInfo();?></p>
        <p>
        <?php _e('Starts at:', 'becTextDomain');
        echo " ";
        printStartDate();
        echo " - ";
        echo get_post_meta(get_the_ID(), 'start_time', true);
        ?>
        </p>
        <p>
        <?php _e('Ends at:', 'becTextDomain');
        echo " ";
        printEndDate();
        echo " - ";
        echo get_post_meta(get_the_ID(), 'end_time', true);
        ?>
        </p>
        <p><?php _e('Address:', 'becTextDomain');
            echo " ";
        echo get_post_meta(get_the_ID(), 'address', true);
        ?></p>
        <p><?php 
        if(get_post_meta(get_the_ID(), 'external_link', true)) {
            _e('Website:', 'becTextDomain');
            echo " ";
            echo "<a href='".get_post_meta(get_the_ID(), 'external_link', true)."'>".get_post_meta(get_the_ID(), 'external_link', true)."</a>";
        }
        ?></p>
        <p><?php _e('Entry fee:', 'becTextDomain');
               echo " ";
        echo "$".get_post_meta(get_the_ID(), 'price', true);
        ?></p>
        <?php printNextEventDates(); ?>
    </div>
    <div class='bec-box-cell'>
        <h3><?php _e('About this event', 'becTextDomain');?></h3>
        <p><?php edit_post_link('✏️', '<p>', '</p>'); ?></p>
        <?php the_content(); ?>
        <hr>
    <a href="../events"><?php _e("Return to archive", 'becTextDomain'); ?></a>
    </div>    
    </div>
    </div>
    </div>
</div>
</section>
<?php endwhile; 
endif; ?>    

<section>
    <div class='bec-container'>
    <h2 class="widget-title">
        <?php _e('Navigate archive', 'becTextDomain'); ?>
    </h2>
    <form id="archiveNavButton" action="../">
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