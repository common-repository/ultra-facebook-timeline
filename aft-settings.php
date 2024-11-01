<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
if(!class_exists('ultra_facebook_timeline_settings'))
{
	class ultra_facebook_timeline_settings
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// register actions
            add_action('init', array($this, 'localize_plugin'));
        	add_action('admin_menu', array(&$this, 'add_menu'));

            // Add style and script
             add_action('wp_print_styles', array($this, 'aft_styles'));
             add_action('wp_print_scripts', array($this, 'aft_scripts'));

            // Create shortcode
            add_shortcode('aft', array($this, 'render_shortcode'));

            // activate shortcode in text widgets
             add_filter('widget_text', 'shortcode_unautop');
             add_filter('widget_text', 'do_shortcode');

		} // END public function __construct

        function localize_plugin(){
            // register styles
            wp_register_style('plugin_css', 'http://fonts.googleapis.com/css?family=Droid+Serif|Open+Sans:400,700', null, aft_VERSION);
            wp_register_style('plugin_reset', aft_URL.'css/reset.css', null, aft_VERSION);
						wp_register_style('plugin_style', aft_URL.'css/style.css', null, aft_VERSION);
						wp_register_style('plugin_fontultra', 'https://maxcdn.bootstrapcdn.com/font-ultra/4.4.0/css/font-ultra.min.css', null, aft_VERSION);
						wp_register_script('modernizr', aft_URL . 'js/modernizr.js', array('jquery'), '1.0.0', true);
						wp_register_script('mainjs', aft_URL . 'js/main.js', array('jquery'), '1.0.0', true);
            // register scripts
            /* wp_register_script('FeedEk', aft_URL . 'js/FeedEk.js', array('jquery'), '1.0.0', true); */
        }

        /* Calling Style */
        function aft_styles() {
            wp_enqueue_style('plugin_css');
						wp_enqueue_style('plugin_reset');
						wp_enqueue_style('plugin_style');
						wp_enqueue_style('plugin_fontultra');
        }// END public function aft_styles()

        /* Calling Script*/
        function aft_scripts() {
            wp_enqueue_script('modernizr');
					  wp_enqueue_script('mainjs');
        }// END public function aft_scripts()

        // Add Shortcode code
        function render_shortcode($atts){
            // Attributes
            extract( shortcode_atts(
                array(
                   'id'   => '',
                   'user_id'    => '',
                   'post_limit' => '10',
                ), $atts )
             );
						$aft_values = get_post_custom($id);
						$aft_username = isset( $aft_values['aft_meta_box_username'] ) ? esc_attr( $aft_values['aft_meta_box_username'][0] ) : "";
					  $aft_limit = isset( $aft_values['aft_meta_box_limit'] ) ? esc_attr( $aft_values['aft_meta_box_limit'][0] ) : "";
					  $aft_node = isset( $aft_values['aft_meta_box_node'] ) ? esc_attr( $aft_values['aft_meta_box_node'][0] ) : "";
					  $aft_simpost = isset( $aft_values['aft_meta_box_simpost'] ) ? esc_attr( $aft_values['aft_meta_box_simpost'][0] ) : "";
						$aft_imgpost = isset( $aft_values['aft_meta_box_imgpost'] ) ? esc_attr( $aft_values['aft_meta_box_imgpost'][0] ) : "";
					  $aft_txtcirc = isset( $aft_values['aft_meta_box_txtcirc'] ) ? esc_attr( $aft_values['aft_meta_box_txtcirc'][0] ) : "";
					  $aft_video = isset( $aft_values['aft_meta_box_video'] ) ? esc_attr( $aft_values['aft_meta_box_video'][0] ) : "";
					  $aft_image = isset( $aft_values['aft_meta_box_image'] ) ? esc_attr( $aft_values['aft_meta_box_image'][0] ) : "";
						$aft_bg = isset( $aft_values['aft_status_background'] ) ? esc_attr( $aft_values['aft_status_background'][0] ) : "";
					  $aft_btnbg = isset( $aft_values['aft_status_btnbg'] ) ? esc_attr( $aft_values['aft_status_btnbg'][0] ) : "";
					  $aft_linecolor = isset( $aft_values['aft_status_linecolor'] ) ? esc_attr( $aft_values['aft_status_linecolor'][0] ) : "";
					  $aft_btncolor = isset( $aft_values['aft_status_btncolor'] ) ? esc_attr( $aft_values['aft_status_btncolor'][0] ) : "";
					  $aft_titlecolor = isset( $aft_values['aft_status_titlecolor'] ) ? esc_attr( $aft_values['aft_status_titlecolor'][0] ) : "";
					  $aft_imgcolor = isset( $aft_values['aft_status_circolor'] ) ? esc_attr( $aft_values['aft_status_circolor'][0] ) : "";
					  $aft_vidcolor = isset( $aft_values['aft_status_vidcolor'] ) ? esc_attr( $aft_values['aft_status_vidcolor'][0] ) : "";
					  $aft_txtcolor = isset( $aft_values['aft_status_txtcolor'] ) ? esc_attr( $aft_values['aft_status_txtcolor'][0] ) : "";
					  $aft_shadcolor = isset( $aft_values['aft_status_shadcolor'] ) ? esc_attr( $aft_values['aft_status_shadcolor'][0] ) : "";
						$aft_usernames = explode(",",$aft_username);
            $userNames  = $aft_usernames;
            $postLimit = $aft_limit;

            $options = get_option('aft_setting');
            $access_token = $options['aft_fb_app_id']."|".$options['aft_fb_app_secret_key'];
						$multiple_posts = array();
						foreach ($userNames as $userName) {
		            if($userName){
									if(empty($postLimit)){
										$postLimit = 10;
									}
		                $graph_url    = "https://graph.facebook.com/".$userName."?fields=posts.limit(".$postLimit."){id,name,source,message,full_picture,updated_time,description,likes,comments,shares}&access_token=". $access_token;
		                try{
											//print_r  (json_decode(@file_get_contents($graph_url), true));
											 array_push($multiple_posts,json_decode(@file_get_contents($graph_url), true));
										}
										catch(Exception $ex){
											echo esc_html("Sorry, we are not able to get this feed. Are you sure, your internet connection is fine ? ");
										}
		            }
						}
						$out ='<section id="cd-timeline" class="cd-container"><div id="fb-root"></div><style>.cd-timeline-block:nth-child(even) .cd-timeline-content::before {  border-right-color: '.$aft_bg.'; } .cd-timeline-content::before { border-left-color: '.$aft_bg.'; } #cd-timeline::before{    background: '.$aft_linecolor.'; }</style>';
						$out .= '
						<div class="cd-timeline-img cd-picture startnode" style="background:'.$aft_shadcolor.';">
							'.$aft_node.'
						</div>';
						if(!empty($multiple_posts)):
						$overall_key = 0;
						foreach ($multiple_posts as $key) {
								$page_posts   = $multiple_posts[$overall_key]['posts']['data'];
								$iteration = 0;
								if(!empty($page_posts)){
								foreach ($page_posts as $post_feed) {
		                $feed_link  =  "http://www.facebook.com/".$post_feed['id'];
										$video = "";
										if(isset($post_feed['source'])):
											$video =  $post_feed['source'];
										endif;
										if(isset($post_feed['message'])):
											$feed_des   =  $post_feed['message'];
										endif;
										$feed_img = "";
										$shr_count = "";
										if(isset($post_feed['full_picture'])):
		                	$feed_img   =  $post_feed['full_picture'];
										endif;
		                $feed_time  =  strtotime($post_feed['updated_time']);
		                $like_count =  count($post_feed['likes']['data']);
		                $cmt_count  =  count($post_feed['likes']['data']);
										if(isset( $post_feed['shares']['count'])):
		                	$shr_count  =  $post_feed['shares']['count'];
										endif;
										$out .='
										<div class="cd-timeline-block">
										';

										if(!empty($feed_img)  && !empty($feed_des)):
												$out .='
												<div class="cd-timeline-img cd-location" style="background:'.$aft_imgcolor.'">
												<img src="'.aft_URL.'img/cd-icon-picture.svg" alt="Picture">
												</div> <!-- cd-timeline-img -->';
										elseif(!empty($feed_img)  && empty($feed_des)):
											$out .='
											<div class="cd-timeline-img cd-location" style="background:'.$aft_imgcolor.'">
											<img src="'.aft_URL.'img/cd-icon-picture.svg" alt="Picture">
											</div> <!-- cd-timeline-img -->';
										elseif (empty($feed_img)  && !empty($feed_des)) :
												$out .='
												<div class="cd-timeline-img cd-picture" style="background:'.$aft_txtcolor.'">
												<img src="'.aft_URL.'img/pencil.png" alt="Picture">
												</div> <!-- cd-timeline-img -->';
										elseif(!empty($video)):
												$out .='
												PKKK
												<div class="cd-timeline-img cd-location" style="background:'.$aft_vidcolor.'">
												<img src="'.aft_URL.'img/cd-icon-movie.svg" alt="Picture">
												</div> <!-- cd-timeline-img -->';
										endif;
										$out .='
												<div class="cd-timeline-content" style="background-color:'.$aft_bg.'">
													<p>';
													if($feed_img && empty($video)):
														if(!empty($aft_imgpost) && !empty($feed_des)){
															$feed_des = substr($feed_des,0,$aft_imgpost)."...";
														}
														$out .=  "<div class='feed_img feed_left' style='color:".$aft_titlecolor."'><img src='".$feed_img."' alt='Feed Image' title='".$feed_des."' /><br /><br />
														".$feed_des."
														</div>";
													elseif($video):
														if(!empty($aft_imgpost) && !empty($feed_des)){
															$feed_des = substr($feed_des,0,$aft_imgpost)."...";
														}
														$video_id = explode("_",$post_feed['id']);
														$out .=  '<div class="feed_img feed_left" style="color:'.$aft_titlecolor.'">
														<div class="fb-video" data-href="https://www.facebook.com/facebook/videos/'.$video_id[1].'" data-width="500">
														</div>
														<br /><br />
														'.$feed_des.'
														</div>';
													else:
														if(!empty($aft_simpost)){
															$feed_des = substr($feed_des,0,$aft_simpost)."...";
														}
														$out .= '<h2 style="color:'.$aft_titlecolor.'">'.$feed_des.'</h2>';
													endif;
													$out .='</p>';
													$feed_des = "";
													$out .='<br />
													<a href="'.$feed_link.'" class="cd-read-more" style="background-color:'.$aft_btnbg.'">Read more</a>
													<span class="cd-date">'.date("F j, Y",$feed_time).'</span>
													</div> <!-- cd-timeline-content -->
											</div> <!-- cd-timeline-block -->';
									  $iteration ++;
										}
									}
										$overall_key ++;
							}
            	$out .= "</section> <!-- cd-timeline -->";
						endif;
            return $out;
        }

        /* add a menu */
        public function add_menu()
        {
            // Add a page to manage this plugin's settings
        	add_submenu_page('edit.php?post_type=facebook-timeline','ultraFacebook Timeline', 'Set Facebook API', 'manage_options', 'ultra_facebook_timeline', array(&$this, 'aft_htu_page'));
        } // END public function add_menu()


        /* Menu Callback */
        public function aft_htu_page()
        {
            if(!current_user_can('manage_options'))
            {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }
            include(sprintf("%s/inc/aft-setting-about.php", dirname(__FILE__)));
        } // END public function aft_htu_page()

        public function aft_setting_option_page()
        {
            if(!current_user_can('manage_options'))
            {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }
            include(sprintf("%s/inc/aft-settion-option.php", dirname(__FILE__)));
        }// END public function aft_htu_page()

        public function aft_layout_setting_option_page()
        {
            if(!current_user_can('manage_options'))
            {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }
            include(sprintf("%s/inc/css-layout-setting.php", dirname(__FILE__)));
        }// END public function aft_htu_page()

    } // END class ultra_facebook_timeline_settings
} // END if(!class_exists('ultra_facebook_timeline_settings'))
