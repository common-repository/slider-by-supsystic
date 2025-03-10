<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 * (c) Armin Ronacher
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Twig_supTwgSsl_Node_Expression_Binary_BitwiseOr extends Twig_supTwgSsl_Node_Expression_Binary
{
    public function operator(Twig_supTwgSsl_Compiler $compiler)
    {
        return $compiler->raw('|');
    }
}
