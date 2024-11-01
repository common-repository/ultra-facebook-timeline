<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
$msg = '';
	if (isset($_POST['aft-submit'])) {
		$msg = 0;
		$aft_setting = array();
		$aft_setting['aft_fb_app_id'] 			=	sanitize_text_field($_POST['aft_fb_app_id']);
		$aft_setting['aft_fb_app_secret_key']	=	sanitize_text_field($_POST['aft_fb_app_secret_key']);

		$options = get_option('aft_setting');
		if (empty($options)) {
			add_option('aft_setting', $aft_setting);
			$msg = 1;
		}
		else{
			update_option( 'aft_setting', $aft_setting );
			$msg = 1;
		}
	}
?>
<?php if ($msg==1) { ?>
	<div class="updated settings-error notice is-dismissible" id="setting-error-settings_updated">
		<p>
			<strong>Settings saved.</strong>
		</p>
	</div>
<?php } ?>

<div class="aft-wrap">
    <h2 class="title">Ultra Facebook Timeline</h2>
    <div class="wrap">
            <h2>Global API Settings</h2>
            <?php $options = get_option('aft_setting'); ?>
            <form method="post" action="">
                <table class="form-table">
                    <tr valign="top"><th scope="row">App ID :</th>
                        <td>
                        	<input type="text" name="aft_fb_app_id"  class="aft_text" value="<?php echo esc_html($options['aft_fb_app_id']); ?>" />
                        </td>
                    </tr>
                    <tr valign="top"><th scope="row">App Secret :</th>
                        <td>
                        	<input type="text" name="aft_fb_app_secret_key"  class="aft_text" value="<?php echo esc_html($options['aft_fb_app_secret_key']); ?>" />
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" class="button-primary" name="aft-submit" value="<?php _e('Save Changes') ?>" />
                </p>
            </form>
        </div>
				<div class="aft-info">
						To get API details open this link <a href="https://developers.facebook.com/" target="_blank">https://developers.facebook.com/</a>, and follow the instructions below. (You are suppose to be - already have an account over facebook)
				</div>
				<div class="aft_video">
					<iframe width="560" height="315" src="https://www.youtube.com/embed/Y9wFBAePLlA" frameborder="0" allowfullscreen></iframe>
					<ul>
						<li>
								First you should have an account with facebook. <a href=" https://developers.facebook.com/" target="_blank">https://developers.facebook.com/</a>, go to Apps -> Register as a Developer
						</li>
						<li>
							 	Apps -> Create a new App.
						</li>
						<li>
							 Complete the wizard.
						</li>
						<li>
							You must have to provide email ID and make the app public.
						</li>
						<li>
								Once done you will see the new App ID and Secret keys in the dashboard.
						</li>
						<li>
							 For more detailed instructions please follow the video.
						</li>
				</div>
</div>
