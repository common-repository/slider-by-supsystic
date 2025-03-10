<?php

/**
 * Class GirdGallery_Ajax_Handler
 * AJAX requests handler
 *
 * @package GirdGallery\Ajax
 * @author Artur Kovalevsky
 */
class SupsysticSlider_Ajax_Handler
{

    /**
     * @var RscSsl_Environment
     */
    protected $environment;

    /**
     * @param RscSsl_Environment $environment
     */
    public function __construct(RscSsl_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @return bool
     */
    public function handle()
    {
        $request = RscSsl_Http_Request::create();

        if ($this->isPostRequest($request)) {
            return $this->handleRequest($request->post);
        } elseif ($this->isGetRequest($request)) {
            return $this->handleRequest($request->query);
        }
    }

    /**
     * @param RscSsl_Http_Parameters $method
     * @return bool
     */
    public function handleRequest(RscSsl_Http_Parameters $method)
    {
        /** @var RscSsl_Mvc_Module $module */
        if (!$method->has('route')) {
            return false;
        }

        $route = $method->get('route');
        $module = (isset($route['module']) ? $route['module'] : $this->environment->getConfig()->get('default_module'));
        $action = (isset($route['action']) ? $route['action'] : 'index');

        if (null !== $module = $this->environment->getModule(strtolower($module))) {
            $controller = $module->getController();

            if ($controller !== null && method_exists($controller, $action = sprintf('%sAction', $action))) {
                return call_user_func_array(array($controller, $action), array($controller->getRequest()));
            }
        }

        return false;
    }

    /**
     *
     * @param RscSsl_Http_Request $request
     * @return bool
     */
    public function isPostRequest(RscSsl_Http_Request $request)
    {
        return ($request->post->has('route'));
    }

    /**
     * @param RscSsl_Http_Request $request
     * @return bool
     */
    public function isGetRequest(RscSsl_Http_Request $request)
    {
        return ($request->query->has('route'));
    }
} 