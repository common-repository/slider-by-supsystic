<?php

/**
 * Describes a logger-aware instance
 */
interface RscSsl_Logger_AwareInterface
{
    /**
     * Sets a logger instance on the object
     *
     * @param RscSsl_Logger_Interface $logger
     * @return null
     */
    public function setLogger(RscSsl_Logger_Interface $logger);
}