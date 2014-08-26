		<?php
		/*
		Plugin Name: HotChalk - NurseEdi
		Plugin URI: http://www.hotchalk.com/
		Version: 03/20/2014 (replace with date of your latest revision)
		Author: Zander Fields
		Description: This is the new Employment Desirability Index plugin.

		This plugin will allow for implementation of the salary widget tool. The salary widget tool allows users to select a local area, state, and job title, and be returned a series of employment statistics based on their selections. All returned data is provided by data downloaded from the Bureau of Labor Statistics.
		*/

		/* Follow the steps below to create a new plugin from this template */

		/* In this file:
		/* Replace Plugin with Nameofyourplugin
		/* Replace HC_PLUGIN_ with HC_NAMEOFYOURPLUGIN_ throughout */
		/* Replace hc-plugin with hc-nameofyourplugin throughout */
		/* Replace HC_Plugin with HC_Nameofyourplugin throughout */
		/* Replace hcPlugin with hcNameofyourplugin throughout */
		/* Replace hc_plugin with hc_nameofyourplugin throughout */

		/* 
			Change the directory name from hc-plugin to hc-nameofyourplugin.
			Change the file name hc-plugin.php to hc-nameofyourplugin.php
			Change the file name hc-plugin.css to hc-nameofyourplugin.css
		*/

		define ('HC_NURSEEDI_VERSION', '08/04/2014');
		define ('HC_NURSEEDI_PLUGIN_URL', plugin_dir_url(__FILE__));
		define ('HC_NURSEEDI_PLUGIN_DIR', plugin_dir_path(__FILE__));
		define ('HC_NURSEEDI_SETTINGS_LINK', '<a href="'.home_url().'/wp-admin/admin.php?page=hc-nurseedi">Settings</a>');


		class HC_NurseEdi {
			/* define any localized variables here */
			private $myPrivateVars;
			private $opt; /* points to any options defined and used in the admin */

			function __construct() {
				/* Best practice is to save all your settings in 1 array */
				/*   Get this array once and reference throughout plugin */
				$this->opt = get_option('hcNurseEdi');
		
				/* You can do things once here when activating / deactivating, such as creating
				     database tables and deleting them. */
				register_activation_hook(__FILE__,array($this,'activate'));
				register_deactivation_hook( __FILE__,array($this,'deactivate'));
		
				/* Enqueue any scripts needed on the front-end */
				add_action('wp_enqueue_scripts', array($this,'frontScriptEnqueue'));
		
				/* Create all the necessary administration menus. */
				/* Also enqueues scripts and styles used only in the admin */
				add_action('admin_menu', array($this,'adminMenu'));
		
				/* adminInit handles all of the administartion settings  */ 
				add_action('admin_init', array($this,'adminInit'));
		
				// if you need anything in the footer, define it here
				add_action('wp_footer', array($this,'footerScript'));
				$ga_plugin = plugin_basename(__FILE__); 
		
				// this code creates the settings link on the plugins page
				add_filter("plugin_action_links_$ga_plugin", array($this,'pluginSettingsLink'));
		
				// create any shortcodes needed
				add_shortcode( 'hc_nurseedi', array($this,'shortcode'));
		    }
	
			// Enqueue any front-end scripts here
			function frontScriptEnqueue() {
				//wp_enqueue_script('swaplogo',HC_PLUGIN_PLUGIN_URL.'js/swaplogo.js',false,null);
			}

		    /* these admin styles are only loaded when the admin settings page is displayed */
			function adminEnqueue() {
				// wp_enqueue_style('hc-plugin-style',HC_PLUGIN_PLUGIN_URL.'css/hc_plugin.css');
			}
	
			// Enqueue any scripts needed in the admin here 
			function adminEnqueueScripts() {
				// wp_enqueue_script('jquery-ui-sortable');
				// wp_enqueue_script('jquery-ui-datepicker');
			}
	
			// code that gets run on plugin activation.
			// create any needed database tables or similar here
			function activate() {
			}

			// code the gets run on plugin de-activation
			// remove any database tables or other settings here
			function deactivate() {
			}
	
			// Setup the admin menu here.  Also enqueues backend styles/scripts
			// images/icon.png is the icon that appears on the admin menu
			function adminMenu() {
				add_menu_page('HotChalk','HotChalk','manage_options','hc_top_menu','',plugin_dir_url(__FILE__).'/images/icon.png', 88.8 ); 
		
				$page = add_submenu_page('hc_top_menu','NurseEdi','NurseEdi','manage_options','hc-nurseedi',array($this,'adminOptionsPage'));
		
				remove_submenu_page('hc_top_menu','hc_top_menu'); // remove extra top level menu item if there
		
				 /* Using registered $page handle to hook stylesheet loading */
				add_action( 'admin_print_styles-' . $page, array($this,'adminEnqueue'));
				add_action( 'admin_print_scripts-' . $page, array($this,'adminEnqueueScripts'));
			}
	
			// settings link on plugins page
			function pluginSettingsLink($links) { 
			  $settings_link = HC_NURSEEDI_SETTINGS_LINK; 
			  array_unshift($links, $settings_link); 
			  return $links; 
			}
	
			/* Define the settings for your plugin here */ 
			/* Create as many sections as needed */ 
			function adminInit(){
				register_setting( 'hcNurseEdiOptions', 'hcNurseEdiOptions', array($this,'optionsValidate'));
				add_settings_section('hcNurseEdiSection1', 'Plugin Settings Section 1', array($this,'sectionText1'), 'hc-NurseEdi');
				add_settings_field('hcNurseEdiSection1', '', array($this,'section1settings'), 'hc-nurseedi', 'hcNurseEdiSection1');
			}
		
			// You can validate input here on saving
			// This gets called when click 'Save Changes' from the admin settings.
			// Process input and then return it
			function optionsValidate($input) {
				return $input;
			}
	
			// Settings section description
			function sectionText1() {
				?>
		        <p>This plugin will allow for implementation of the social sciences version of the salary widget tool. The salary widget tool allows users to select a local area, state, and job title, and be returned a series of employment statistics based on their selections. All returned data is provided by data downloaded from the Bureau of Labor Statistics.</p>
		        <?php
			}
	
			// Example setting in admin
			/*
			function section1settings() {
				echo '<div class="section1">';
			    echo '<label>Setting 1 </label><input type="text" name="hcNurseEdiOptions[setting1]" value="'.$this->opt['setting1'].'" />';
				echo '</div>';
			}
			*/
			// Example shortcode
			// [hc_plugin parm1="parm1_setting"]
			function shortcode( ) {
		
				ob_start(); ?>
		<script language="javascript" type="text/javascript">

			function getXMLHTTP() { //function to return the xml http object
				var xmlhttp=false;	
				try{
					xmlhttp=new XMLHttpRequest();
				}
				catch(e)	{		
					try{			
						xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
					}
					catch(e){
						try{
						xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
						}
						catch(e1){
							xmlhttp=false;
						}
					}
				}
		 	
				return xmlhttp;
		    }
	
				function getState(jobId,stateId) 
			{		
			
					var strURL="http://hczander.wpengine.com/wp-content/plugins/hc-nurseedi/findState.php?job="+jobId+"&state="+stateId;
					var req = getXMLHTTP();
			
					if (req) {
				
						req.onreadystatechange = function() {
							if (req.readyState == 4) {
								// only if "OK"
								if (req.status == 200) {	
													
									document.getElementById('statediv').innerHTML=req.responseText;											
								} else {
									alert("There was a problem while using XMLHTTP:\n" + req.statusText);
								}
							}				
						}			
						req.open("GET", strURL, true);
						req.send(null);
				
				}	
			
		
			}
				
			function getTop(jobId) 
			{	
				var strURL="http://hczander.wpengine.com/wp-content/plugins/hc-nurseedi/getTopData.php?job="+jobId;
				var req = getXMLHTTP();
	
				if (req) 
				{
		
					req.onreadystatechange = function() {
						if (req.readyState == 4) {
							// only if "OK"
							if (req.status == 200) {						
								document.getElementById('jobdatadiv').innerHTML=req.responseText;						
							} else {
								alert("There was a problem while using XMLHTTP:\n" + req.statusText);
							}
						}				
					}			
					req.open("GET", strURL, true);
					req.send(null);
				}
		
			}
			function getStateData(stateId,jobId) 
			{	
				var strURL="http://hczander.wpengine.com/wp-content/plugins/hc-nurseedi/getStateData.php?state="+stateId+"&job="+jobId;
				var req = getXMLHTTP();
	
				if (req) 
				{
		
					req.onreadystatechange = function() {
						if (req.readyState == 4) {
							// only if "OK"
							if (req.status == 200) {						
								document.getElementById('statedatadiv').innerHTML=req.responseText;						
							} else {
								alert("There was a problem while using XMLHTTP:\n" + req.statusText);
							}
						}				
					}			
					req.open("GET", strURL, true);
					req.send(null);
				}
		
			}
			
		</script>
		<?php
	
		try 
		{
			$db = new PDO("mysql:host=localhost;dbname=wp_hczander","censored","censored");
		}	catch (Exception $e) 
			{
				echo "Could not connect to database.";
				exit;
			}
		
		$stmt = $db->prepare("SELECT DISTINCT OCC_TITLE 
				FROM Nursing_Job_List
				ORDER BY OCC_TITLE ASC");
	
	
		?>

			<h2>Welcome to Hotchalk's Employment Desirability Center</h2>
		<form>

		    <p>Job Title</p>
		    <p>
		
				<select id="select2" class="required" name="job" onChange="getState(this.value,''); getTop(this.value);getStateData(this.value,'');">
				<option value="">Select a Job Title</option>
				<? if ($stmt->execute()) {
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
				<option value='<?=$row['OCC_TITLE']?>'><?=$row['OCC_TITLE']?></option>
				<? } }?>
				</select>
		
			</p>

		    <p id="state">State</p>
		    <ul id="statediv">
				<select name="state" >
					<option>Select State First</option>
		        </select></ul>

		    

		    <p>
				<ul>
					<li class="area_name">Local Area</li>
					<li class="prim_state">State(s)</li>
					<li class="tot_emp">Total Employment</li>
					<li class="jobs_1000">Jobs Per 1,000</li>
					<li class="a_mean">Annual Average Salary</li>
				</ul>
			</p>	
		    <p><ul id="jobdatadiv"></ul></p>
		
		
			<p><ul id="statedatadiv"></ul></p>
		
	

		</form>
				<?php return ob_get_clean(); 
			}
	
			// footer scripts		
			function footerScript () {
				?>
				<script type="text/javascript">
				// any needed javascript code here - goes in footer
		        </script>
		        <?php
			}
	
			/* the Settings page for this plugin */
			function adminOptionsPage() { ?>
				<div id="hc_nurseedi">
				<h2>(NurseEDI) - HotChalk, Inc. v<?php echo HC_NURSEEDI_VERSION; ?></h2>
				<form method="post" action="options.php">
				<?php settings_fields('hcNurseEdiOptions'); ?>
				<?php do_settings_sections('hc-nurseedi'); ?>
				</form></div>
				<?php
			}
		}

		$hcPlugin = new HC_NurseEdi();
		?>