<?php

/**
 * Class SupsysticSlider_Core_Module
 * Core module
 *
 * @package SupsysticSlider\Core
 * @author Artur Kovalevsky
 */
class SupsysticSlider_Core_Module extends RscSsl_Mvc_Module
{

    /**
     * {@inheritdoc}
     */
    public function onInit()
    {
        parent::onInit();
        $path = dirname(dirname(dirname(dirname(__FILE__))));
        $url = plugins_url(basename($path));
        $config = $this->getEnvironment()->getConfig();
        $hookName = 'admin_enqueue_scripts';
        $dynamicHookName = is_admin() ? $hookName : 'wp_enqueue_scripts';

        $config->add('plugin_url', $url);
        $config->add('plugin_path', $path);

		$this->registerTwigFunctions();
        add_action('admin_enqueue_scripts', array($this, 'enqueueAssets'));
        add_action('admin_enqueue_scripts', array($this, 'dequeueAssets'));
        add_action($dynamicHookName, array($this, 'loadSslNonces'), 1);
        add_filter('gg_hooks_prefix', array($this, 'addHooksPrefix'), 10, 1);
    }

    public function enqueueAssets()
    {
        if ($this->isPluginPage()) {
            wp_enqueue_script('notice',
                $this->getLocationUrl() . '/assets/js/notice.js',
                array('jquery'),
                '1.0',
                true
            );
        }
    }

    public function dequeueAssets() {
        /*
            Fix for conflict with Revolution Slider
        */
        wp_dequeue_style('wp-jquery-ui-dialog');
        wp_dequeue_style('revslider-global-styles');
    }

    /**
     * Adds the plugin's hooks prefix to the hook name
     *
     * @param string $hook The name of the hook
     * @return string
     */
    public function addHooksPrefix($hook)
    {
        $config = $this->getEnvironment()->getConfig();

        return $config->get('hooks_prefix') . $hook;
    }

    public function afterUiLoaded(Callable $callback)
    {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException('$callback must be a callable');
        }
        add_action($this->addHooksPrefix('after_ui_loaded'), $callback);
    }

    public function loadSslNonces() {
        $userCanEdit = false;
        $alowedRoles = array();
          $settings = get_option('ss_settings');
          if ($settings && isset($settings['access_roles'])) {
              $alowedRoles = $settings['access_roles'];
          }
          $current_user = wp_get_current_user();
          if ($current_user) {
              foreach ($current_user->roles as $role) {
                  if (in_array($role, $alowedRoles)) {
                 $userCanEdit = true;
                  }
              }
          }
  
        $environment = $this->getEnvironment();
        $path = $environment->getConfig()->get('plugin_url').'/app/assets/js/sslnonce.js';
        if ( is_admin() && (current_user_can('administrator') || $userCanEdit) ) {
          $nonce = wp_create_nonce('ssl_nonce');
          wp_register_script( 'ssl_nonce', $path, array(), '0.01', true );
          wp_enqueue_script( 'ssl_nonce' );
          wp_add_inline_script( 'ssl_nonce', 'var SSL_NONCE = "'.$nonce.'"' );
          wp_add_inline_script( 'ssl_nonce', 'let sslNewHref = jQuery("[href=\'admin.php?page=supsystic-slider\']").attr("href"); jQuery("[href=\'admin.php?page=supsystic-slider\']").attr("href", sslNewHref+"&nonce='.$nonce.'"); ' );
       }
       if ( !is_admin() ) {
          $nonce = wp_create_nonce('ssl_nonce_frontend');
          wp_register_script( 'ssl_nonce_frontend', $path, array(), '0.01', true );
          wp_enqueue_script( 'ssl_nonce_frontend' );
          wp_add_inline_script( 'ssl_nonce_frontend', 'var SSL_NONCE_FRONTEND = "'.$nonce.'"' );
       }
     }

	public function getProUrl($params = null) {
		$config = $this->getConfig();
		return $config->get('page_url') . (strpos($params, '?') === 0 ? '' : '?') . $params;
	}

	public function buildProUrl(array $parameters = array())
	{
		$config = $this->getEnvironment()->getConfig();
		$homepage = 'https://supsystic.com/plugins/slider/';
		$campaign = 'slider';

		if (!array_key_exists('utm_source', $parameters)) {
			$parameters['utm_source'] = 'plugin';
		}

		if (!array_key_exists('utm_campaign', $parameters)) {
			$parameters['utm_campaign'] = $campaign;
		}

		return $homepage . '?' . http_build_query($parameters);
	}

	public function getPluginDirectoryUrl($path)
	{
		return plugin_dir_url($this->getEnvironment()->getPluginPath() . '/index.php') . '/' . $path;
	}

	private function registerTwigFunctions()
	{

      $twig = $this->getTwig();

		$twig->addFunction(
			new Twig_supTwgSsl_SimpleFunction('plugin_directory_url', array($this, 'getPluginDirectoryUrl'))
		);
		$twig->addFunction(
			new Twig_supTwgSsl_SimpleFunction('build_pro_url', array($this, 'buildProUrl'))
		);
        $twig->addFunction(
			new Twig_supTwgSsl_SimpleFunction('translate', array($this, 'translate'))
        );
        if (function_exists('dump') && $this->getEnvironment()->isDev()) {
            $twig->addFunction(
				new Twig_supTwgSsl_SimpleFunction('dump', 'dump')
			);
        }
		if (function_exists('preg_replace')) {
			$twig->addFilter(
				new Twig_supTwgSsl_SimpleFilter('preg_replace', array($this, 'addPregReplaceFilter'))
			);
		}

    $twig->addFunction(
          new Twig_supTwgSsl_SimpleFunction(
              'translate', array($this, 'translate')
          )
      );
      $twig->addFunction(
          new Twig_supTwgSsl_SimpleFunction(
              'getProUrl', array($this, 'getProUrl')
          )
      );
      $config = $this->getEnvironment()->getConfig();
      $twig->addGlobal('SS_PLUGIN_URL', SS_PLUGIN_URL);
      $twig->addGlobal('SS_PLUGIN_VERSION', $config->get('plugin_version'));
      $twig->addGlobal('SS_PLUGIN_NAME', $config->get('plugin_name'));
      global $current_user;
      $twig->addGlobal('SS_USER_NAME', $current_user->user_firstname.' '.$current_user->user_lastname);
      $twig->addGlobal('SS_USER_EMAIL', $current_user->user_email);
      $twig->addGlobal('SS_WEBSITE', get_bloginfo('url'));

      $show = true;
      $acRemind = get_option('ss_ac_remind', false);
      if (!empty($acRemind)) {
        $currentDate = date('Y-m-d h:i:s');
        if ($currentDate > $acRemind) {
          $show = true;
        } else {
          $show = false;
        }
      }
      $acSubscribe = get_option('ss_ac_subscribe', false);
      if (!empty($acSubscribe)) {
        $show = false;
      }
      $acDisabled = get_option('ss_ac_disabled', false);
      if (!empty($acDisabled)) {
        $show = false;
      }

      $twig->addGlobal('SS_AC_SHOW', $show);

	}

	public function checkIsGlobalClassExists($name, $prefix)
	{
		$class = false;

		if(!empty($name) && !empty($prefix)) {
			// For plugins on old framework
			$className = $name . ucfirst($prefix);

			if(class_exists($className)) {
				$class = true;
			}
		}

		return $class;
	}
}
