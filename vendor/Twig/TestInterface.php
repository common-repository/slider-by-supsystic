<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Represents a template test.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @deprecated since 1.12 (to be removed in 2.0)
 */
interface Twig_supTwgSsl_TestInterface
{
    /**
     * Compiles a test.
     *
     * @return string The PHP code for the test
     */
    public function compile();
}
