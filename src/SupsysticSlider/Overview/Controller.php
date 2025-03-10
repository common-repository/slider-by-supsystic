<?php


class SupsysticSlider_Overview_Controller extends SupsysticSlider_Core_BaseController
{

    public function indexAction(RscSsl_Http_Request $request)
    {
        if (!$this->_checkNonce($request)) die();
        $serverSettings = $this->getServerSettings();
        $config = $this->getEnvironment()->getConfig();
        global $current_user;
		wp_get_current_user();

        return $this->response(
            '@overview/index.twig',
            array(
                'serverSettings' => $serverSettings,
                'news' => $this->loadNews($config['post_url']),
                'contactForm' => array(
                    'name' => $current_user->user_firstname,
                    'email' => $current_user->user_email,
                    'website' => get_bloginfo('url')
                ),
            )
        );
    }

    public function sendMailAction(RscSsl_Http_Request $request) {
        if (!$this->_checkNonce($request)) die();
        $mail = $request->post['route']['data'];

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $mail['name'] . ' <' . $mail['email'] . '>'
        );

        $message = array(
            'Name: ' . $mail['name'],
            'E-mail: ' .  $mail['email'],
            'Website: ' . $mail['website'],
            'Subject: ' . $mail['subject'],
            'Topic: ' . str_replace('_', ' ', ucfirst($mail['question'])),
            'Мessage: ' . $mail['message']
        );
        $message = implode('<br>', $message);

        $config = $this->getEnvironment()->getConfig();

        wp_mail($config['mail'], $mail['subject'], $message, $headers);

        $response = array(
            'success' => true,
            'message' => $this->translate('Your message successfully send. We contact you soon.')
        );

        $errors = $this->getMailErrors();
        if (!empty($errors)) {
            $response = array(
                'success' => false,
                'message' => $errors[0]
            );
        }

        return $this->response(RscSsl_Http_Response::AJAX, $response);
    }
    /**
   * @param RscSsl_Http_Request $request
   */
  public function sendSubscribeMailAction(RscSsl_Http_Request $request)
  {
      if (!$this->_checkNonce($request)) die();
      $apiUrl = 'https://supsystic.com/wp-admin/admin-ajax.php';
      $reqUrl = $apiUrl . '?action=ac_get_plugin_installed';
      $config = $this->getEnvironment()->getConfig();
      $mail = $request->post['route']['data'];
      $isPro = !empty($config->get('is_pro')) ? true : false;
      $data = array(
          'body' => array(
              'key' => 'kJ#f3(FjkF9fasd124t5t589u9d4389r3r3R#2asdas3(#R03r#(r#t-4t5t589u9d4389r3r3R#$%lfdj',
              'user_name' => $mail['username'],
              'user_email' => $mail['email'],
              'site_url' => get_bloginfo('wpurl'),
              'site_name' => get_bloginfo('name'),
              'plugin_code' => $config->get('plugin_name'),
              'is_pro' => $isPro,
          ),
      );

      $response = wp_remote_post(
          $reqUrl,
          $data
      );
      if (is_wp_error($response)) {
          $response = array(
              'success' => false,
              'message' => $this->translate('Some errors.')
          );
      } else {
          $response = array(
              'success' => true,
              'message' => $this->translate('Thank you for subscribtions.')
          );
          update_option('ss_ac_subscribe', true);
      }
      return $this->response(RscSsl_Http_Response::AJAX, $response);
  }

  /**
   * @param RscSsl_Http_Request $request
   */
  public function sendSubscribeRemindAction(RscSsl_Http_Request $request)
  {
      if (!$this->_checkNonce($request)) die();
      update_option('ss_ac_remind', date("Y-m-d h:i:s", time() + 86400));
      $response = array ('success' => true);
      return $this->response(RscSsl_Http_Response::AJAX, $response);
  }

  /**
   * @param RscSsl_Http_Request $request
   */
  public function sendSubscribeDisableAction(RscSsl_Http_Request $request)
  {
      if (!$this->_checkNonce($request)) die();
      update_option('ss_ac_disabled', true);
      $response = array ('success' => true);
      return $this->response(RscSsl_Http_Response::AJAX, $response);
  }
	protected function getServerSettings() {
		global $wpdb;

		$phpVersion = phpversion();
		$settings = array(
			'Operating System' => array('value' => PHP_OS),
			'PHP Version' => array('value' => PHP_VERSION),
			'Server Software' => array('value' => $_SERVER['SERVER_SOFTWARE']),
			'MySQL version' => array('value' => $this->translate('No detected')),
			'MySQLi driver' => array('value' => $wpdb->use_mysqli ? 'Yes' : 'No'),
			'PHP Allow URL Fopen' => array('value' => ini_get('allow_url_fopen') ? 'Yes' : 'No'),
			'PHP Memory Limit' => array('value' => ini_get('memory_limit')),
			'PHP Max Post Size' => array('value' => ini_get('post_max_size')),
			'PHP Max Upload Filesize' => array('value' => ini_get('upload_max_filesize')),
			'PHP Max Script Execute Time' => array('value' => ini_get('max_execution_time')),
			'PHP EXIF Support' => array('value' => extension_loaded('exif') ? 'Yes' : 'No'),
			'PHP EXIF Version' => array('value' => phpversion('exif')),
			'PHP XML Support' => array('value' => extension_loaded('libxml') ? 'Yes' : 'No', 'error' => !extension_loaded('libxml')),
			'PHP CURL Support' => array('value' => extension_loaded('curl') ? 'Yes' : 'No', 'error' => !extension_loaded('curl')),
		);

		if(function_exists('mysqli_get_server_info') && function_exists('mysqli_connect')) {
			$settings['MySQL version']['value'] = @mysqli_get_server_info(@mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD));
		}

		return $settings;
	}

     protected function getMailErrors() {
        global $ts_mail_errors;

        if (!isset($ts_mail_errors)) {
            $ts_mail_errors = array();
        }

        return $ts_mail_errors;
    }

    protected function loadNews ($url) {
        $news = wp_remote_retrieve_body(wp_remote_get($url));

        return $news;
    }
}
