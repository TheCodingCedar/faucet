<?php

class The99Bitcoins_BtcFaucet_Manager
{
	protected $config = array(
		'plugin' => '99bitcoins-btc-faucet/',
		'templates' => '../templates/',
		'version' => '1.0.0',
		'prefix' => 't99f_',
	);

	/** @var The99Bitcoins_BtcFaucet_Plugin[] */
	protected $faucets = array();

	public function __construct($config = array())
	{
		$this->config = $config + $this->config;

        add_action('init', array($this, 'init'));
	}

	public function cron()
	{
		foreach ($this->faucets as $faucet) {
			$faucet->cronPayout();
			$faucet->cronPayment();
			$faucet->cronPriceSync();
			$faucet->cronMaintenance();
			$faucet->cronStats();
		}
	}

	public function init()
	{
		$locale = get_locale();
		load_textdomain('99btc-bf', WP_LANG_DIR . '/plugins/99btc-bf-' . $locale . '.mo');
		load_plugin_textdomain('99btc-bf', false, $this->config['plugin'] . 'languages/');

        add_filter('pre_set_site_transient_update_plugins', array($this, 'adminFilterCheckUpdate'));
        add_filter('plugins_api', array($this, 'adminFilterPluginsApi'), 10, 3);
        add_filter('pre_set_site_transient_update_plugins', array($this, 'adminFilterPreSetSiteTransientUpdatePlugins'), 10, 2);

		register_post_type('t99f-faucet', array(
			'label' => __('Faucets', '99btc-bf'),
			'labels' => array(
				'name' => __('Faucets', '99btc-bf'),
				'singular_name' => __('Faucet', '99btc-bf'),
				'add_new_item' => __('Add New Faucet', '99btc-bf'),
				'edit_item' => __('Rename Faucet', '99btc-bf'),
				'new_item' => __('New Faucet', '99btc-bf'),
				'view_item' => __('View Faucet', '99btc-bf'),
				'view_items' => __('View Faucets', '99btc-bf'),
				'search_items' => __('Search Faucets', '99btc-bf'),
				'not_found' => __('No faucets found.', '99btc-bf'),
				'not_found_in_trash' => __('No faucets found in Trash.', '99btc-bf'),
				'all_items' => __('All Faucets', '99btc-bf'),
				'archives' => __('Faucet Archives', '99btc-bf'),
				'attributes' => __('Faucet Attributes', '99btc-bf'),
				'insert_into_item' => __('Insert into faucet', '99btc-bf'),
				'uploaded_to_this_item' => __('Updated to this faucet', '99btc-bf'),
				'filter_items_list' => __('Filter faucets list', '99btc-bf'),
				'items_list_navigation' => __('Faucets list navigation', '99btc-bf'),
				'items_list' => __('Faucets list', '99btc-bf'),
				'menu_name' => __('Faucets', '99btc-bf'),
			),
			'public' => false,
			'show_ui' => true,
			'supports' => array('title'),
			'register_meta_box_cb' => array($this, 'registerMetaBoxCb'),
		));

		add_filter('cron_schedules', array($this, 'wpFilterCronSchedules'));
		add_action('admin_menu', array($this, 'adminActionMenu'));
		add_action('admin_enqueue_scripts', array($this, 'adminActionEnqueueScripts'));
		add_action('admin_print_styles', array($this, 'adminActionPrintStyles'));
		add_action('wp_enqueue_scripts', array($this, 'wpActionEnqueueScripts'));
		add_action('show_user_profile', array($this, 'wpEditUserProfile'));
		add_action('edit_user_profile', array($this, 'wpEditUserProfile'));
		add_action('user_profile_update_errors', array($this, 'wpEditUserProfileUpdate'), 10, 3);
		add_action('wp_insert_post', array($this, 'wpInsertPost'), 10, 3);

		add_shortcode('btc-faucet-form', array($this, 'wpPageForm'));
		add_shortcode('btc-faucet-form-text', array($this, 'wpPageFormText'));
		add_shortcode('btc-faucet-address-check', array($this, 'wpPageAddressCheck'));
		add_shortcode('btc-faucet-total-paid', array($this, 'wpPageTotalPaid'));
		add_shortcode('btc-faucet-ref-link', array($this, 'wpPageRefLink'));

		add_action('wp_ajax_' . $this->config['prefix'] . 'transaction', array($this, 'wpAjaxTransaction'));
		add_action('wp_ajax_nopriv_' . $this->config['prefix'] . 'transaction', array($this, 'wpAjaxTransaction'));
		add_action('wp_ajax_' . $this->config['prefix'] . 'history', array($this, 'wpAjaxHistory'));
		add_action('wp_ajax_nopriv_' . $this->config['prefix'] . 'history', array($this, 'wpAjaxHistory'));
		add_action('wp_ajax_' . $this->config['prefix'] . 'submit', array($this, 'wpAjaxSubmit'));
		add_action('wp_ajax_nopriv_' . $this->config['prefix'] . 'submit', array($this, 'wpAjaxSubmit'));

		if (!($faucets = $this->getFaucets()) && get_option('The99Bitcoins_BtcFaucet_Plugin', null) !== null) {
			if (in_array('administrator', wp_get_current_user()->roles)) {
				$admin = get_current_user_id();
			} else {
				$admin = get_users(array('role' => 'administrator'));
				$admin = $admin ? $admin[0]->ID : 0;
			}
			$options = get_option('The99Bitcoins_BtcFaucet_Plugin', null);
			$cronStamp = get_option('The99Bitcoins_BtcFaucet_Plugin-cron-stamp', null);

			$faucetId = wp_insert_post(array(
				'post_author' => $admin,
				'post_type' => 't99f-faucet',
				'post_title' => __('FaucetPay', '99btc-bf'),
				'post_status' => 'publish',
			));

			if ($options) {
				delete_option('The99Bitcoins_BtcFaucet_Plugin');
				update_option($this->config['prefix'] . $faucetId . '_main', $options, 'no');
			}
			if ($cronStamp) {
				delete_option('The99Bitcoins_BtcFaucet_Plugin-cron-stamp');
				update_option($this->config['prefix'] . $faucetId . '_cron_stamp', $cronStamp, 'no');
			}

			/** @var wpdb $wpdb */
			global $wpdb;
			$wpdb->query("RENAME TABLE {$wpdb->prefix}99btc_bf_ban_address TO {$wpdb->prefix}t99f_{$faucetId}_ban_address");
			$wpdb->query("RENAME TABLE {$wpdb->prefix}99btc_bf_ban_ip TO {$wpdb->prefix}t99f_{$faucetId}_ban_ip");
			$wpdb->query("RENAME TABLE {$wpdb->prefix}99btc_bf_claim_ips TO {$wpdb->prefix}t99f_{$faucetId}_claim_ips");
			$wpdb->query("RENAME TABLE {$wpdb->prefix}99btc_bf_claim_payouts TO {$wpdb->prefix}t99f_{$faucetId}_claim_payouts");
			$wpdb->query("RENAME TABLE {$wpdb->prefix}99btc_bf_info_address TO {$wpdb->prefix}t99f_{$faucetId}_info_address");
			$wpdb->query("RENAME TABLE {$wpdb->prefix}99btc_bf_info_ip TO {$wpdb->prefix}t99f_{$faucetId}_info_ip");
			$wpdb->query("RENAME TABLE {$wpdb->prefix}99btc_bf_info_user TO {$wpdb->prefix}t99f_{$faucetId}_info_user");
			$wpdb->query("RENAME TABLE {$wpdb->prefix}99btc_bf_kv TO {$wpdb->prefix}t99f_{$faucetId}_kv");
			$wpdb->query("RENAME TABLE {$wpdb->prefix}99btc_bf_scheduled_payouts TO {$wpdb->prefix}t99f_{$faucetId}_scheduled_payouts");
			$wpdb->query("RENAME TABLE {$wpdb->prefix}99btc_bf_stats TO {$wpdb->prefix}t99f_{$faucetId}_stats");
			$wpdb->query("RENAME TABLE {$wpdb->prefix}99btc_bf_white_address TO {$wpdb->prefix}t99f_{$faucetId}_white_address");
		} elseif (!$faucets && !get_option($this->config['prefix'] . 'main', array())) {
			if (in_array('administrator', wp_get_current_user()->roles)) {
				$admin = get_current_user_id();
			} else {
				$admin = get_users(array('role' => 'administrator'));
				$admin = $admin ? $admin[0]->ID : 0;
			}

			update_option($this->config['prefix'] . 'main', array(
				'optout' => false,
				'pay' => true,
			), 'no');

			wp_insert_post(array(
				'post_author' => $admin,
				'post_type' => 't99f-faucet',
				'post_title' => __('FaucetPay', '99btc-bf'),
				'post_status' => 'publish',
			));
		}

		$faucets = $faucets ? $faucets : $this->getFaucets();
		foreach ($faucets as $faucet) {
			$this->faucets[$faucet->ID] = new The99Bitcoins_BtcFaucet_Plugin(array(
				'post' => $faucet,
				'prefix' => $this->config['prefix'] . $faucet->ID . '_',
			) + $this->config);
		}
	}

	protected function shortCode($args, $body, $code, $method)
	{
		$args = $args ? $args : array();
		if (empty($args['id']) && $this->faucets) {
			$ids = array_keys($this->faucets);
			sort($ids, SORT_NUMERIC);
			$args['id'] = reset($ids);
		}
		if ($args['id'] && !empty($this->faucets[$args['id']]) && method_exists($this->faucets[$args['id']], $method)) {
			if ($method === 'wpPageForm' || $method === 'wpPageAddressCheck') {
				add_filter('do_rocket_generate_caching_files', '__return_false');
			}
			return $this->faucets[$args['id']]->$method($args, $body, $code);
		}
		return '';
	}

	public function wpPageForm($args, $body, $code)
	{
		return $this->shortCode($args, $body, $code, __FUNCTION__);
	}

	public function wpPageFormText($args, $body, $code)
	{
		return $this->shortCode($args, $body, $code, __FUNCTION__);
	}

	public function wpPageAddressCheck($args, $body, $code)
	{
		return $this->shortCode($args, $body, $code, __FUNCTION__);
	}

	public function wpPageTotalPaid($args, $body, $code)
	{
		return $this->shortCode($args, $body, $code, __FUNCTION__);
	}

	public function wpPageRefLink($args, $body, $code)
	{
		return $this->shortCode($args, $body, $code, __FUNCTION__);
	}

	protected function ajax($args, $method)
	{
		$args = $args ? $args : array();
		if (empty($args['id']) && !empty($_REQUEST['t99fid'])) {
			$args['id'] = $_REQUEST['t99fid'];
		}
		if (empty($args['id']) && $this->faucets) {
			$ids = array_keys($this->faucets);
			sort($ids, SORT_NUMERIC);
			$args['id'] = reset($ids);
		}
		if ($args['id'] && !empty($this->faucets[$args['id']]) && method_exists($this->faucets[$args['id']], $method)) {
			echo $this->faucets[$args['id']]->$method();
		}
		wp_die();
	}

	public function wpAjaxTransaction()
	{
		$this->ajax(array(), __FUNCTION__);
	}

	public function wpAjaxHistory()
	{
		$this->ajax(array(), __FUNCTION__);
	}

	public function wpAjaxSubmit()
	{
		$this->ajax(array(), __FUNCTION__);
	}

	public function install()
	{
		if (!wp_next_scheduled('The99BitcoinsBtcFaucetCron')) {
			wp_schedule_event(current_time('timestamp'), 'the99btc_payout', 'The99BitcoinsBtcFaucetCron');
		}

//		wp_remote_request('http://btc-faucet.stats.99bitcoins.com/faucet.php?track=1', array(
//			'headers' => array(
//				'Referer' => site_url(),
//				'Plugin-Version' => $this->config['version'],
//			),
//		));
	}

	public function uninstall()
	{
		wp_clear_scheduled_hook('The99BitcoinsBtcFaucetCron');
	}

	public function wpFilterCronSchedules(array $values)
	{
		$values['the99btc_payout'] = array(
			'interval' => MINUTE_IN_SECONDS * 20,
			'display' => __('Every 20 minutes', '99btc-bf')
		);
		return $values;
	}

	public function adminFilterCheckUpdate($transient)
	{
		if (!is_object($transient)) {
			return $transient;
		}
		$options = get_option(__CLASS__, array());
		$response = wp_remote_request('http://btc-faucet.plugin.99bitcoins.com/check.json', array(
			'user-agent' => '99bitcoins BTC Faucet',
			'headers' => array(
				'Referer' => site_url(),
				'Plugin-Version' => $this->config['version'],
			),
		));
		if (is_array($response) && $response['response']['code'] == 200) {
			$response = json_decode($response['body']);
		} else {
			$response = null;
		}

		if (!empty($options['optout'])) {
			unset($response->package);
		}

		if ($response && version_compare($this->config['version'], $response->new_version, '<')) {
			$transient->response[$response->plugin] = $response;
		} elseif ($response) {
			$transient->no_update[$response->plugin] = $response;
		}
		return $transient;
	}

	public function adminFilterPluginsApi($result, $action, $args)
	{
		if ($action == 'plugin_information' && $args->slug == $this->config['url']) {
			$options = get_option(__CLASS__, array());
			$response = wp_remote_request('http://btc-faucet.plugin.99bitcoins.com/latest.json', array(
				'user-agent' => '99bitcoins BTC Faucet',
				'headers' => array(
					'Referer' => site_url(),
					'Plugin-Version' => $this->config['version'],
				),
			));
			if (is_array($response) && $response['response']['code'] == 200) {
				$response = json_decode($response['body']);
			} else {
				$response = null;
			}

			if (!empty($options['optout'])) {
				unset($response->package);
			}

			if ($response) {
				$result = $response;

				$response = wp_remote_request('http://btc-faucet.plugin.99bitcoins.com/readme.txt', array(
					'user-agent' => '99bitcoins BTC Faucet',
					'headers' => array(
						'Referer' => site_url(),
						'Plugin-Version' => $this->config['version'],
					),
				));
				if (is_array($response) && $response['response']['code'] == 200) {
					$result->sections['changelog'] = $response['body'];
				}
			}
		}
		return $result;
	}

	public function adminFilterPreSetSiteTransientUpdatePlugins($value, $transient)
	{
		if ($transient == 'update_plugins' && is_object($value)) {
			if (isset($value->response[$this->config['plugin'] . 'index.php']->id)) {
				unset($value->response[$this->config['plugin'] . 'index.php']);
			}
			if (isset($value->no_update[$this->config['plugin'] . 'index.php']->id)) {
				unset($value->no_update[$this->config['plugin'] . 'index.php']);
			}
		}
		return $value;
	}

	public function registerMetaBoxCb()
	{
		remove_meta_box('slugdiv', 'faucet', 'normal');
	}

	public function adminActionMenu()
	{
		add_submenu_page('edit.php?post_type=t99f-faucet', __('Support', '99btc-bf'), __('Support', '99btc-bf'), 'manage_options', 'suport', array($this, 'adminPageSupport'));
	}

	public function adminActionEnqueueScripts($hook)
	{
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('the99btc-bf-js-cookie', $this->config['plugin_url'] . 'assets/js/jquery.cookie.js', array('jquery'), $this->config['version'], true);
		wp_enqueue_script('the99btc-bf-js-admin', $this->config['plugin_url'] . 'assets/js/admin.js', array('the99btc-bf-js-cookie'), $this->config['version'], true);
	}

	public function adminActionPrintStyles()
	{
		wp_enqueue_style('jquery-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
		wp_enqueue_style('the99btc-bf-css-admin', $this->config['plugin_url'] . 'assets/css/admin.css', array(), $this->config['version']);
	}

	public function wpActionEnqueueScripts()
	{
		wp_enqueue_script('the99btccharjs', $this->config['plugin_url'] . 'assets/js/Chart.min.js');
		wp_enqueue_script('the99btc-bf-js-cookie', $this->config['plugin_url'] . 'assets/js/jquery.cookie.js', array('jquery'), $this->config['version'], true);
		wp_enqueue_script('the99btc-bf-js-faucet', $this->config['plugin_url'] . 'assets/js/faucet.js', array('the99btc-bf-js-cookie'), $this->config['version'], true);
		wp_enqueue_style('the99btc-bf-css-faucet', $this->config['plugin_url'] . 'assets/css/faucet.css', array(), $this->config['version']);
	}

	public function wpEditUserProfile(WP_User $profile)
	{
		$variables = array();
		$variables['record'] = $profile;
		$this->render('wp-edit-user-profile', $variables, 'empty');
	}

	/**
	 * @param WP_Error $errors
	 * @param $update
	 * @param WP_User $user
	 */
	public function wpEditUserProfileUpdate(&$errors, $update, &$user)
	{
		if (!empty($_POST['the99btc_address_BCH']) && !The99Bitcoins_BtcFaucet_Currency_BCH::validateAddress($_POST['the99btc_address_BCH'])) {
			$errors->add(99005, esc_html__('BCH Address is not valid', '99btc'));
		}
		if (!empty($_POST['the99btc_address_BLK']) && !The99Bitcoins_BtcFaucet_Currency_BLK::validateAddress($_POST['the99btc_address_BLK'])) {
			$errors->add(99005, esc_html__('BLK Address is not valid', '99btc'));
		}
		if (!empty($_POST['the99btc_address_BTC']) && !The99Bitcoins_BtcFaucet_Currency_BTC::validateAddress($_POST['the99btc_address_BTC'])) {
			$errors->add(99001, esc_html__('BTC Address is not valid', '99btc'));
		}
		if (!empty($_POST['the99btc_address_BTX']) && !The99Bitcoins_BtcFaucet_Currency_BTX::validateAddress($_POST['the99btc_address_BTX'])) {
			$errors->add(99005, esc_html__('BTX Address is not valid', '99btc'));
		}
		if (!empty($_POST['the99btc_address_DASH']) && !The99Bitcoins_BtcFaucet_Currency_DASH::validateAddress($_POST['the99btc_address_DASH'])) {
			$errors->add(99002, esc_html__('DASH Address is not valid', '99btc'));
		}
		if (!empty($_POST['the99btc_address_DOGE']) && !The99Bitcoins_BtcFaucet_Currency_DOGE::validateAddress($_POST['the99btc_address_DOGE'])) {
			$errors->add(99002, esc_html__('DOGE Address is not valid', '99btc'));
		}
		if (!empty($_POST['the99btc_address_ETH']) && !The99Bitcoins_BtcFaucet_Currency_ETH::validateAddress($_POST['the99btc_address_ETH'])) {
			$errors->add(99003, esc_html__('ETH Address is not valid', '99btc'));
		}
		if (!empty($_POST['the99btc_address_LTC']) && !The99Bitcoins_BtcFaucet_Currency_LTC::validateAddress($_POST['the99btc_address_LTC'])) {
			$errors->add(99004, esc_html__('LTC Address is not valid', '99btc'));
		}
		if (!empty($_POST['the99btc_address_POT']) && !The99Bitcoins_BtcFaucet_Currency_POT::validateAddress($_POST['the99btc_address_POT'])) {
			$errors->add(99005, esc_html__('POT Address is not valid', '99btc'));
		}
		if (!empty($_POST['the99btc_address_PPC']) && !The99Bitcoins_BtcFaucet_Currency_PPC::validateAddress($_POST['the99btc_address_PPC'])) {
			$errors->add(99005, esc_html__('PPC Address is not valid', '99btc'));
		}
		if (!empty($_POST['the99btc_address_XPM']) && !The99Bitcoins_BtcFaucet_Currency_XPM::validateAddress($_POST['the99btc_address_XPM'])) {
			$errors->add(99005, esc_html__('XPM Address is not valid', '99btc'));
		}

		if (!$errors->get_error_codes() && !empty($_POST['the99btc_address_BCH'])) {
			update_user_meta($user->ID, 'the99btc_address_BCH', $_POST['the99btc_address_BCH']);
		}
		if (!$errors->get_error_codes() && !empty($_POST['the99btc_address_BLK'])) {
			update_user_meta($user->ID, 'the99btc_address_BLK', $_POST['the99btc_address_BLK']);
		}
		if (!$errors->get_error_codes() && !empty($_POST['the99btc_address_BTC'])) {
			update_user_meta($user->ID, 'the99btc_address_BTC', $_POST['the99btc_address_BTC']);
		}
		if (!$errors->get_error_codes() && !empty($_POST['the99btc_address_BTX'])) {
			update_user_meta($user->ID, 'the99btc_address_BTX', $_POST['the99btc_address_BTX']);
		}
		if (!$errors->get_error_codes() && !empty($_POST['the99btc_address_DASH'])) {
			update_user_meta($user->ID, 'the99btc_address_DASH', $_POST['the99btc_address_DASH']);
		}
		if (!$errors->get_error_codes() && !empty($_POST['the99btc_address_DOGE'])) {
			update_user_meta($user->ID, 'the99btc_address_DOGE', $_POST['the99btc_address_DOGE']);
		}
		if (!$errors->get_error_codes() && !empty($_POST['the99btc_address_ETH'])) {
			update_user_meta($user->ID, 'the99btc_address_ETH', $_POST['the99btc_address_ETH']);
		}
		if (!$errors->get_error_codes() && !empty($_POST['the99btc_address_LTC'])) {
			update_user_meta($user->ID, 'the99btc_address_LTC', $_POST['the99btc_address_LTC']);
		}
		if (!$errors->get_error_codes() && !empty($_POST['the99btc_address_POT'])) {
			update_user_meta($user->ID, 'the99btc_address_POT', $_POST['the99btc_address_POT']);
		}
		if (!$errors->get_error_codes() && !empty($_POST['the99btc_address_PPC'])) {
			update_user_meta($user->ID, 'the99btc_address_PPC', $_POST['the99btc_address_PPC']);
		}
		if (!$errors->get_error_codes() && !empty($_POST['the99btc_address_XPM'])) {
			update_user_meta($user->ID, 'the99btc_address_XPM', $_POST['the99btc_address_XPM']);
		}
	}

	public function wpInsertPost($id, WP_Post $post)
	{
		if ($post->post_type === 't99f-faucet') {
			$base = get_option($this->config['prefix'] . 'main', array());
			$option = get_option($this->config['prefix'] . $id . '_' . 'main', array());
			if (array_diff_key($base, $option)) {
				$option += $base;
				update_option($this->config['prefix'] . $id . '_' . 'main', $option, 'no');
			}
		}
	}

	public function adminPageSupport()
	{
		global $wpdb;
		$variables = array();
		$variables['options'] = get_option($this->config['prefix'] . 'main', array());

		if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['action']) && $_POST['action'] == 'optout') {
			$variables['notice_css_class'] = 'notice notice-success';
			$variables['notice_message'] = esc_html__('Opt out status was switched', '99btc-bf');

			$variables['options']['optout'] = empty($variables['options']['optout']);
			update_option($this->config['prefix'] . 'main', $variables['options'], 'no');

			foreach ($this->faucets as $id => $post) {
				$options = get_option($this->config['prefix'] . $id . '_main', array());
				$options['optout'] = $variables['options']['optout'];
				$options['pay'] = true;
				update_option($this->config['prefix'] . $id . '_main', array(), 'no');
			}
		}
		if (empty($variables['options']['optout']) && $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['action']) && $_POST['action'] == 'send') {
			$variables['notice_css_class'] = 'notice notice-success';
			$variables['notice_message'] = esc_html__('Thank you, message has been sent', '99btc-bf');

			$message = !empty($_POST['message']) ? nl2br(htmlspecialchars(trim($_POST['message']))) : 'No message';

			$files = array();
			if (!empty($_POST['diagnostic'])) {
				$dir = get_temp_dir();
				if (wp_is_writable($dir)) {
					$filename = get_temp_dir() . md5(microtime(true)) . '.sql.gz';
					$f = gzopen($filename, 'wb9');

					foreach ($wpdb->get_results("SHOW TABLES LIKE '{$wpdb->prefix}t99f_%'", ARRAY_A) as $table) {
						$table = reset($table);
						$create = $wpdb->get_var("SHOW CREATE TABLE {$table}", 1);
						gzwrite($f, "DROP TABLE {$table} IF EXISTS;". "\n");
						gzwrite($f, $create . ";\n");
						foreach ($wpdb->get_results("SELECT * FROM " . $table, ARRAY_A) as $row) {
							foreach ($row as $k => $data) {
								$row[$k] = "'" . esc_sql($data) . "'";
							}
							gzwrite($f, 'INSERT INTO wp_' . substr($table, strlen($wpdb->prefix)) . ' (' . implode(', ', array_keys($row)) . ') VALUES (' . implode(', ', $row) . ");\n");
						}
					}

					gzwrite($f, "DELETE FROM wp_options WHERE option_name LIKE 't99f_%';". "\n");
					foreach ($wpdb->get_results("SELECT * FROM {$wpdb->prefix}options WHERE option_name LIKE 't99f_%' ORDER BY option_name ASC", ARRAY_A) as $row) {
						foreach ($row as $k => $data) {
							$row[$k] = "'" . esc_sql($data) . "'";
						}
						gzwrite($f, 'INSERT INTO wp_options (' . implode(', ', array_keys($row)) . ') VALUES (' . implode(', ', $row) . ");\n");
					}

					gzclose($f);
					$message .= "<br><br>Diagnostic should be attached";
					$files[] = $filename;
				} else {
					$message .= "<br><br>No diagnostic was attached because temp dir was not writable";
				}
			} else {
				$message .= "<br><br>No diagnostic was attached";
			}

			ob_start();
			phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES | INFO_ENVIRONMENT | INFO_VARIABLES);
			$content = ob_get_contents();
			ob_end_clean();
			$message .= '<br><br>' . $content;

			/** @var WP_User $user */
			$user = wp_get_current_user();
			$headers = array(
				'Content-Type: text/html',
				'Reply-To: ' . $user->user_email,
				'Sender: ' . $user->user_email,
			);

			wp_mail('plugin@99bitcoins.zendesk.com', 'Support requested for website ' . get_bloginfo('url'), $message, implode("\n\r", $headers) , $files);

			foreach ($files as $file) {
				unlink($file);
			}
		}
		$this->render('admin-support', $variables, 'empty');
	}

	/**
	 * @return WP_Post[]
	 */
	protected function getFaucets()
	{
		$query = new WP_Query();
		return $query->query(array(
			'post_type' => 't99f-faucet',
			'orderby' => 'post_title',
			'order' => 'ASC',
			'nopaging' => true,
			'post_status' => 'any',
		));
	}

	protected function render($_template, $_vairables = array(), $_layout = '')
	{
		extract($_vairables);
		ob_start();
		include $this->config['templates'] . $_template . '.php';
		$_content = ob_get_contents();
		ob_end_clean();
		if ($_layout) {
			include $this->config['templates'] . 'layout' . DIRECTORY_SEPARATOR . $_layout . '.php';
		} else {
			echo $_content;
		}
	}
}
