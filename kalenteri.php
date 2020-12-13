<div id="calender_section">
		<h2>
        	<a href="javascript:void(0);" onclick="getCalendar('calendar_div','<?php echo date('Y',strtotime($date.' - 1 Month')); ?>','<?php echo date('m',strtotime($date.' - 1 Month')); ?>');">&lt;&lt;</a>
            <select name="month_dropdown" class="month_dropdown dropdown"><?php echo getAllMonths($dateMonth); ?></select>
			<select name="year_dropdown" class="year_dropdown dropdown"><?php echo getYearList($dateYear); ?></select>
            <a href="javascript:void(0);" onclick="getCalendar('calendar_div','<?php echo date('Y',strtotime($date.' + 1 Month')); ?>','<?php echo date('m',strtotime($date.' + 1 Month')); ?>');">&gt;&gt;</a>
        </h2>
		<div id="event_list" class="none"></div>
		<div id="calender_section_top">
			<ul>
				<li><b>Ma</b></li>
				<li><b>Ti</b></li>
				<li><b>Ke</b></li>
				<li><b>To</b></li>
				<li><b>Pe</b></li>
				<li><b>La</b></li>
				<li><b>Su</b></li>
			</ul>
		</div>
		<div id="calender_section_bot">
			<ul>
			<?php
			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);
				$dayCount = 1; 
				for($cb=1;$cb<=$boxDisplay;$cb++){
					if(($cb >= $currentMonthFirstDay || $currentMonthFirstDay == 1) && $cb <= ($totalDaysOfMonthDisplay)){ // aloittaa viiikon maanantaista jos kosket asiat leipoo
						//Current date
						$currentDate = $dateYear.'-'.$dateMonth.'-'.$dayCount;
						$test = strtotime($currentDate);
						$eventNum = 0;
						$sql = $db->query("SELECT * FROM session ORDER BY ID DESC LIMIT 1"); // Hakee sessionid:n
						if ($sql->num_rows > 0) {
							while ($row = $sql->fetch_assoc()) {
								$sessionID = $row["sessionkey"];
							}
						}
						//Get number of events based on the current date
						$result = $db->query("SELECT Description FROM work WHERE Dates = '".$test."' AND UserID = '".$sessionID."'");
						if (!$result) {
							trigger_error('Invalid query: ' . $db->error);
						}
						$eventNum = $result->num_rows;
						//Define date cell color
						if(strtotime($currentDate) == strtotime(date("d-m-Y"))){
							echo '<li date="'.$currentDate.'" class="grey date_cell">';
						}elseif($eventNum > 0){
							echo '<li date="'.$currentDate.'" class="light_sky date_cell">';
						}else{
							echo '<li date="'.$currentDate.'" class="date_cell">';
						}
						//Date cell
						echo '<span>';
						echo $dayCount;
						echo ($eventNum > 0)?'<a id="link" href="javascript:;" onclick="getEvents(\''.$currentDate.'\');"></a>':'';
						echo '</span>';
						
						
						//Hover event popup
						echo '<div id="date_popup_'.$currentDate.'" class="date_popup_wrap none">';
						echo '<div class="date_window">';
						echo '<div class="popup_event">Työt ('.$eventNum.')</div>';
						echo '</div></div>';
						
						echo '</li>';
						$dayCount++;
			?>
			<?php }else{ ?>
				<li><span>&nbsp;</span></li>
			<?php } } ?>
			</ul>
		</div>
	</div>

	<script type="text/javascript">
		function getCalendar(target_div,year,month){
			$.ajax({
				type:'POST',
				url:'functions_c.php',
				data:'func=getCalender&year='+year+'&month='+month,
				success:function(html){
					$('#'+target_div).html(html);
				}
			});
		}
		function getEvents(date){
			$.ajax({
				type:'POST',
				url:'functions_c.php',
				data:'func=getEvents&date='+date,
				success:function(html){
					$('#event_list').html(html);
					$('#event_list').slideDown('slow');
				}
			});
		}
		
		function addEvent(date){
			$.ajax({
				type:'POST',
				url:'functions_c.php',
				data:'func=addEvent&date='+date,
				success:function(html){
					$('#event_list').html(html);
					$('#event_list').slideDown('slow');
				}
			});
		}
		
		$(document).ready(function(){
			$('.date_cell').click(function(){
				date = $(this).attr('date');
				//$(".date_popup_wrap").fadeOut();
				//$("#date_popup_"+date).fadeIn();	
			});
			$('.date_cell').mouseleave(function(){
			 	$(".date_popup_wrap").fadeOut();		
			});
			$('.month_dropdown').on('change',function(){
				getCalendar('calendar_div',$('.year_dropdown').val(),$('.month_dropdown').val());
			});
			$('.year_dropdown').on('change',function(){
				getCalendar('calendar_div',$('.year_dropdown').val(),$('.month_dropdown').val());
			});
			    $(document).click(function(){
			 	$('#event_list').slideUp('slow');
			});
		});
	</script>