<?php
/*
Plugin Name: Financial Freedom Graph
Plugin URI: http://learnfinancialplanning.com
Description: Chart your path to financial freedom by setting and achieving your public goals.
Author: Learn Financial Planning
Version: 1.0
Stable tag: 1.0
Author URI: http://learnfinancialplanning.com
Development: All development provided by Sprout Venture @ http://sproutventure.com
Support: https://redmine.sproutventure.com
Tags: financial, graphs, weight-loss, widget, money, seo

Installation:
	1. Download the plugin and unzip it (didn't you already do this?).
	2. Upload the 'financial-freedom-graphs' folder into your wp-content/plugins/ directory.
	3. Go to the Plugins page in your WordPress Administration area and click 'Activate' next to Financial Freedom Graph.
	4. Go to Appearance > Widgets to add and configure your financial graphs.
		a. Title => The title of the widget
		b. The Goal => What is the goal? Example, $1,000,000
		c. Currently => Used to state where you are towards your goal. Example, $23,234
		d. Text Color => Text color for current state. Example, white or #ffffff (Use HEX colors or CSS color names only - http://www.w3schools.com/CSS/css_colornames.asp)
		d. Background Color => background color for graph. Example, black or #000000 (Use HEX colors or CSS color names only - http://www.w3schools.com/CSS/css_colornames.asp)
		d. Bar Background Color => the bar background color. Example, red or #FF0000 (Use HEX colors or CSS color names only - http://www.w3schools.com/CSS/css_colornames.asp)
	5. Have fun and succeed at your goals!
	X. Extra Credit - Customize the goal template in graph-template.php
		

Notes:
	

Version history:

.9 UI changes and ready for release
.6 Bug Fix and updated descriptions
.5 Pre-release version ready for testing

*/

/*
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, version 2.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
*/

function percent($num_amount, $num_total) {
	$count1 = $num_amount / $num_total;
	$count2 = $count1 * 100;
	$count = number_format($count2, 0);
	echo $count;
	}
	
class Goals {
    function Goals() {
    }

    function init() {
        if (!$options = get_option('widget_goal_graphs'))
            $options = array();
            
        $widget_ops = array('classname' => 'widget_goal_graphs', 'description' => 'Financial Freedom Graph');
        $control_ops = array('width' => 400, 'height' => 350, 'id_base' => 'ggmulti');
        $name = 'Financial Freedom Graph';
        
        $registered = false;
        foreach (array_keys($options) as $o) {
            if (!isset($options[$o]['title']))
                continue;
                
            $id = "ggmulti-$o";
            $registered = true;
            wp_register_sidebar_widget($id, $name, array(&$this, 'widget'), $widget_ops, array( 'number' => $o ) );
            wp_register_widget_control($id, $name, array(&$this, 'control'), $control_ops, array( 'number' => $o ) );
        }
        if (!$registered) {
            wp_register_sidebar_widget('ggmulti-1', $name, array(&$this, 'widget'), $widget_ops, array( 'number' => -1 ) );
            wp_register_widget_control('ggmulti-1', $name, array(&$this, 'control'), $control_ops, array( 'number' => -1 ) );
        }
    }
    
    function widget($args, $widget_args = 1) {
        extract($args);
        global $post;

        if (is_numeric($widget_args))
            $widget_args = array('number' => $widget_args);
        $widget_args = wp_parse_args($widget_args, array( 'number' => -1 ));
        extract($widget_args, EXTR_SKIP);
        $options_all = get_option('widget_goal_graphs');
        if (!isset($options_all[$number]))
            return;
        $options = $options_all[$number];
		$goal_output		= ereg_replace("[^A-Za-z0-9]", "", $options["graph_goal"] );
		$update_output		= ereg_replace("[^A-Za-z0-9]", "", $options["graph_update"] );
        echo $before_widget . $before_title;
        echo $options["title"] . $after_title;
		include("graph-template.php");
        echo $after_widget;
    }
    
    function control($widget_args = 1) {
        global $wp_registered_widgets;
        static $updated = false;

        if ( is_numeric($widget_args) )
            $widget_args = array( 'number' => $widget_args );
        $widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
        extract($widget_args, EXTR_SKIP);
        $options_all = get_option('widget_goal_graphs');
        if (!is_array($options_all))
            $options_all = array();
            
        if (!$updated && !empty($_POST['sidebar'])) {
            $sidebar = (string)$_POST['sidebar'];

            $sidebars_widgets = wp_get_sidebars_widgets();
            if (isset($sidebars_widgets[$sidebar]))
                $this_sidebar =& $sidebars_widgets[$sidebar];
            else
                $this_sidebar = array();

            foreach ($this_sidebar as $_widget_id) {
                if ('widget_goal_graphs' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number'])) {
                    $widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
                    if (!in_array("ggmulti-$widget_number", $_POST['widget-id']))
                        unset($options_all[$widget_number]);
                }
            }
            foreach ((array)$_POST['widget_goal_graphs'] as $widget_number => $widget_many_instance) {
                if (!isset($widget_many_instance['title']) && isset($options_all[$widget_number]))
                    continue;
                $title = wp_specialchars($widget_many_instance['title']);
                $graph_goal = wp_specialchars($widget_many_instance['graph_goal']);
				$graph_update = wp_specialchars($widget_many_instance['graph_update']);
				$graph_background = wp_specialchars($widget_many_instance['graph_background']);
				$graph_bar = wp_specialchars($widget_many_instance['graph_bar']);
				$graph_text = wp_specialchars($widget_many_instance['graph_text']);
                $options_all[$widget_number] = array('title' => $title, 'graph_goal' => $graph_goal, 'graph_update' => $graph_update, 'graph_text' => $graph_text,  'graph_update' => $graph_update, 'graph_background' => $graph_background, 'graph_bar' => $graph_bar, 'graph_text' => $graph_text);
            }
            update_option('widget_goal_graphs', $options_all);
            $updated = true;
        }

		if (-1 == $number) {
            $title = '';
            $graph_goal = '';
			$graph_update = '';
			$graph_background = '';
			$graph_bar = '';
			$graph_text = '';
            $number = '%i%';
        } else {
            $title = wp_specialchars($options_all[$number]['title'] );
            $graph_goal = wp_specialchars($options_all[$number]['graph_goal']);
			$graph_update = wp_specialchars($options_all[$number]['graph_update']);
			$graph_background = wp_specialchars($options_all[$number]['graph_background']);
			$graph_bar = wp_specialchars($options_all[$number]['graph_bar']);
			$graph_text = wp_specialchars($options_all[$number]['graph_text']);
        }
		echo '';
        ?>
            <p>
				<label for="goalgraph-title"><?php _e('Title:'); ?> <br/><small>Example, Emergency Fund.</small>
					<input class="widefat" type="text" id="widget_goal_graphs-<?php echo $number; ?>-title" name="widget_goal_graphs[<?php echo $number; ?>][title]" value="<?php echo $title; ?>" />
				</label>
			</p>
			<p>
				<label for="goal">The Goal: <br/><small>Example, $10,000.</small>
						<input class="widefat" type="text" id="widget_goal_graphs-<?php echo $number; ?>-graph_goal" name="widget_goal_graphs[<?php echo $number; ?>][graph_goal]" value="<?php echo $graph_goal; ?>" />
				</label>
			</p><p>
				<label for="current">Currently at: <br/><small>Example, $4,242.</small>
					<input class="widefat" type="text" id="widget_goal_graphs-<?php echo $number; ?>-graph_update" name="widget_goal_graphs[<?php echo $number; ?>][graph_update]" value="<?php echo $graph_update; ?>" />
				</label>
			</p><p>
				<label for="background">Text Color: <br/><small>Example, <span style="color:white; background:black">white</span> or <span style="color:white; background:black">#ffffff</span>. <br/>(Use HEX colors or CSS color names only)</small>
					<input class="widefat" type="text" id="widget_goal_graphs-<?php echo $number; ?>-graph_text" name="widget_goal_graphs[<?php echo $number; ?>][graph_text]" value="<?php echo $graph_text; ?>" />
				</label>
			</p><p>
				<label for="bar">Background Color: <br/><small>Example, <span style="color:#000">black</span> or <span style="color:#000">#000000</span>. <br/>(Use HEX colors or CSS color names only)</small>
					<input class="widefat" type="text" id="widget_goal_graphs-<?php echo $number; ?>-graph_background" name="widget_goal_graphs[<?php echo $number; ?>][graph_background]" value="<?php echo $graph_background; ?>" />
				</label>
			</p><p>
				<label for="bar">Bar Background Color: <br/><small>Example, <span style="color:#ff0000">red</span> or <span style="color:#ff0000">#ff0000</span>. <br/>(Use HEX colors or CSS color names only)</small>
					<input class="widefat" type="text" id="widget_goal_graphs-<?php echo $number; ?>-graph_bar" name="widget_goal_graphs[<?php echo $number; ?>][graph_bar]" value="<?php echo $graph_bar; ?>" />
				</label>

                <input type="hidden" id="widget_goal_graphs-<?php echo $number; ?>-submit" name="widget_goal_graphs[<?php echo $number; ?>][submit]" value="1" />
            </p>
        <?php
    }
    
}

$gdm = new Goals();
add_action('widgets_init', array($gdm, 'init'));

?>