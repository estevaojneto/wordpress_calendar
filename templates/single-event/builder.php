<?php
function calcStartDate(){
	return calcRecurringEventDates(get_post_meta( get_the_ID(), 'start_date', true), get_post_meta( get_the_ID(), 'recurrency', true), date('Y-m-d'));
}

function printStartDate()
{
	$startDate = calcStartDate();
	echo $startDate[0];
	return;
}

function printEndDate()
{
	$startDate = calcStartDate();
	$eventLength = calcEventLengthInDays(get_post_meta( get_the_ID(), 'start_date', true), get_post_meta( get_the_ID(), 'end_date', true));
	echo calcEventEndDate($startDate[0], $eventLength);
	return;
}

function printFeaturedImageIfExists(){
	if(wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' )[0]){
		echo "<div class='bec-box-cell'>";
		echo "<img src='".wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' )[0]."' alt='event image'>";
		echo "</div>";
	} 
}

function printNextEventDates(){	
	if(get_post_meta( get_the_ID(), 'recurrency', true) > 0){
		$startDate = calcStartDate();
		if(count($startDate) > 0){
			echo "<p><small>";
			_e('Next instances of this event on this month', 'becTextDomain');
			echo ":<br>";
			$i = 0;
			foreach($startDate as $date){
				if($i == 4)
					break;
				echo $date."<br>";
				++$i;
			}
		}
		else{
			echo "<p><small>";
			_e('The next instance of this event will only happen next month.', 'becTextDomain');
		}
		echo "</small></p>";
	}
	return;
}

function printRecurrencyInfo()
{
	_e("This event", 'becTextDomain');
	echo " ";
	switch(get_post_meta( get_the_ID(), 'recurrency', true))	{
		case 0:
			_e("is one-time only!", 'becTextDomain');
			break;
		case 1:
			_e("happens every day.", 'becTextDomain');
			break;
		case 7:
			_e("happens every week.", 'becTextDomain');
			break;
		case 30:
			_e("happens every month on this same date.", 'becTextDomain');
			break;
		default: 
			echo "ERR";
			break;		
	}
	return;
}