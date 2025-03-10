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
 * Interface implemented by token parsers.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
interface Twig_supTwgSsl_TokenParserInterface
{
    /**
     * Sets the parser associated with this token parser.
     */
    public function setParser(Twig_supTwgSsl_Parser $parser);

    /**
     * Parses a token and returns a node.
     *
     * @return Twig_supTwgSsl_NodeInterface
     *
     * @throws Twig_supTwgSsl_Error_Syntax
     */
    public function parse(Twig_supTwgSsl_Token $token);

    /**
     * Gets the tag name associated with this token parser.
     *
     * @return string The tag name
     */
    public function getTag();
}
