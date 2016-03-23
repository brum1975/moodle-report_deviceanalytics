<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Defines the version and other meta-info about the plugin
 *
 * Setting the $plugin->version to 0 prevents the plugin from being installed.
 * See https://docs.moodle.org/dev/version.php for more info.
 *
 * @package    tool_deviceanalytics
 * @copyright  2016 Mark Heumueller <mark.heumueller@gmx.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('lib.php');
require_login();
if ((isset($_SERVER['HTTPS'])) and ('on' == $_SERVER['HTTPS'])) {
    $CFG->httpswwwroot = 'https://'.$_SERVER['HTTP_HOST'];
} else {
    $CFG->httpwwwroot = 'http://'.$_SERVER['HTTP_HOST'];
}
$systemcontext = context_system::instance();
$PAGE->set_context($systemcontext);
$PAGE->set_url('/admin/tool/deviceanalytics/storage_helper_page.php');
$PAGE->set_pagelayout('redirect');
$PAGE->requires->css('/admin/tool/deviceanalytics/css/storage_helper_page_css.css');
$PAGE->requires->js('/admin/tool/deviceanalytics/libs/jquery-1.12.2.min.js', true);

$data_storage = new deviceanalytics_data_storage();
$insert_id = $data_storage->deviceanalytics_user_loggedin();
$CFG->additionalhtmlhead .= '<noscript>
<meta http-equiv="refresh" content="0;url='.$CFG->wwwroot.'">
</noscript>';
echo $OUTPUT->header();
?>
<script type="text/javascript">
    $( document ).ready(function() {
    	if(<?php echo $insert_id;?> == 0){
    		//window.location.replace("<?php echo $CFG->wwwroot; ?>");
    	}else{
    		var ajaxurl = 'ajaxcall.php?' + 'sesskey=' + M.cfg.sesskey + '&insert_id=' + <?php echo $insert_id; ?>;
    		var screensize = {
    			'device_display_size_x': screen.width, 
    			'device_display_size_y': screen.height, 
    			'device_window_size_x': $(window).width(), 
    			'device_window_size_y': $(window).height()
    		}
    		$.ajax({
	    		type: "GET",
	  			url: ajaxurl,
	  			data: screensize,
			}).done(function(html) {
	  			console.log(html);
	  			//window.location.replace("<?php echo $CFG->wwwroot; ?>");
			});
    	}
	});
</script>
<?php
echo $OUTPUT->footer();