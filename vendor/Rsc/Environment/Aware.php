<?php


class RscSsl_Environment_Aware implements RscSsl_Environment_AwareInterface
{

    /**
     * @var RscSsl_Environment
     */
    protected $environment;

    /**
     * Sets the environment.
     *
     * @param RscSsl_Environment $environment
     */
    public function setEnvironment(RscSsl_Environment $environment)
    {
        $this->environment = $environment;
    }
}