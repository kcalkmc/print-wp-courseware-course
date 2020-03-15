<?php

/* Add the code below to your functions file.  
Do NOT add the opening and closing PHP indicators (<?php and ?>) 

This is DOWN n' DIRTY code for you to take and customize as you want. 

There is no explicit nor implied warrantty.
It is not guaranteeed to work on your system, and, I do not provide support. 

There is a forced page break before each Module. Lessons print conitnuously below the Module.
This code printed 35 pages for one course. The course has 25 lessons spread over 8 modules. 

*/ 

add_shortcode( 'print_the_course', 'print_course_contents'); /* Add [print_the_course] to the page you have created */

function print_course_contents() {
	global $wpdb;
						// I loaded the course number. You can replace it with a variable and add an outer loop to print all your courses. 
	$cur_modules = $wpdb->get_results( "SELECT *  FROM yourDBprefix_wpcw_modules WHERE parent_course_id = 1 ORDER BY module_order", OBJECT );

	if ( $cur_modules ) {

		foreach ( $cur_modules as $one_module ) {	
			$output .= '<br style="page-break-before:always;">';
   			$output .= '<span style="font-size:24px;font-weight:bold;">' . $one_module->module_order . '. '  . $one_module->module_title . '</span>';
            $output .= '<br><span style="font-size:16px;">' . $one_module->module_desc . '</span>';
			
			$cur_units_meta = $wpdb->get_results( "SELECT * FROM yourDBprefix_wpcw_units_meta WHERE parent_module_id = $one_module->module_id AND parent_course_id = 1 ORDER BY unit_order ASC", OBJECT );
			if ( $cur_units_meta ) {
				$output .= '<p>There are ' . count( $cur_units_meta  ) . ' lessons in this module.</p>';
				foreach ( $cur_units_meta as $one_meta ) {
					
					$the_lesson = $wpdb->get_results( "SELECT * from yourDBprefix_posts WHERE ID = $one_meta->unit_id ", OBJECT );
					if ( $the_lesson ) {
						foreach ( $the_lesson as $one_lesson ) {
							$output .= '<h2>' . $one_lesson->post_title . '</h2>';
							$output .= '<h4>' . $one_lesson->post_content . '</h4>';
						}
					}
				}
			}
			
		}
	} 
	return $output;
}
?>