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
 * Twig_supTwgSsl_NodeVisitorInterface is the interface the all node visitor classes must implement.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
interface Twig_supTwgSsl_NodeVisitorInterface
{
    /**
     * Called before child nodes are visited.
     *
     * @return Twig_supTwgSsl_NodeInterface The modified node
     */
    public function enterNode(Twig_supTwgSsl_NodeInterface $node, Twig_supTwgSsl_Environment $env);

    /**
     * Called after child nodes are visited.
     *
     * @return Twig_supTwgSsl_NodeInterface|false The modified node or false if the node must be removed
     */
    public function leaveNode(Twig_supTwgSsl_NodeInterface $node, Twig_supTwgSsl_Environment $env);

    /**
     * Returns the priority for this visitor.
     *
     * Priority should be between -10 and 10 (0 is the default).
     *
     * @return int The priority level
     */
    public function getPriority();
}
