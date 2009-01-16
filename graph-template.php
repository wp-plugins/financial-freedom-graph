<style>
/*
	Use this template to modify and style your graphs.	
	*/
	
#goalgraph .goal {  
	width:100%; 
	height:20px; 
	border:solid 1px #555;
}
#goalgraph .progress {
	padding-left:3px; 
	height:20px; 
}
#goalgraph .goal-info { 
	margin: 0;
	font-weight: bold; 
	text-align: left; 
	font-size: .9em; 
}
#goalgraph .ad { 
	margin: 0; 
	text-align: right; 
	font-size: .8em; 
}
</style>

<div id="goalgraph">
	
	<!-- Start Goal Info above chart -->
	<p class="goal-info">Goal: <?php echo $options["graph_goal"] ?><br />Complete: <?php echo $options["graph_update"] ?></p>
	<!-- // End Goal Info -->
	<!-- Start the Chart -->
	<div class="goal" style="background:<?php echo $options["graph_background"] ?>;">
		
		<div class="progress" style="background:<?php echo $options["graph_bar"] ?>; width:<?php percent($update_output, $goal_output); ?>%;">
			
			<font style="padding-left:<?php percent($update_output, $goal_output); ?>%; color:<?php echo $options["graph_text"] ?>"><?php percent($update_output, $goal_output); ?>%</font>
		
		</div> <!-- // .progress --><!-- // End Chart -->
	</div> <!-- // .goal -->
	
	<!-- Start sponsored by link -->
	<p class="ad">Financial Freedom Widget provided by <a href="http://learnfinancialplanning.com" title="Learn Financial Planning">Learn Financial Planning</a>.</p>
	<!-- // Start sponsored by link -->
	
</div> <!-- // .goalgraph -->