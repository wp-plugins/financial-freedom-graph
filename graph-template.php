<!-- 
	Use this template to modify the style of the output of your graphs.	
	-->
<style>
.goal {  
	width:100%; 
	height:20px; 
	border:solid 1px #555;
}
.progress {
	padding-left:3px; 
	height:20px; 
}
.goal-info { 
	margin: 0; 
	text-align: center; 
	font-size: .9em; 
}
</style>

<div id="goalgraph">
	
	<!-- // Start Goal Info above chart -->
	<p class="goal-info">
		Goal: <?php echo $options["graph_goal"] ?> ~ Complete: <?php echo $options["graph_update"] ?>
	</p>
	<!-- // End Goal Info -->
	<!-- // Start the Chart -->
	<div class="goal" style="background:<?php echo $options["graph_background"] ?>;">
		
		<div class="progress" style="background:<?php echo $options["graph_bar"] ?>; width:<?php percent($update_output, $goal_output); ?>%;">
			
			<font style="padding-left:<?php percent($update_output, $goal_output); ?>%; color:<?php echo $options["graph_text"] ?>"><?php percent($update_output, $goal_output); ?>%</font>
		
		</div> <!-- // .progress -->
	<!-- // End Chart -->
	
	</div> <!-- // .goal -->
	
</div> <!-- // .goalgraph -->