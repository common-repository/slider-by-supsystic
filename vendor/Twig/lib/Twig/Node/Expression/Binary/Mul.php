<?php

use Twig\Node\Expression\Binary\MulBinary;

class_exists('Twig\Node\Expression\Binary\MulBinary');

//@trigger_error(sprintf('Using the "Twig_supTwgSsl_Node_Expression_Binary_Mul" class is deprecated since Twig version 2.7, use "Twig\Node\Expression\Binary\MulBinary" instead.'), E_USER_DEPRECATED);

if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Node\Expression\Binary\MulBinary" instead */
    class Twig_supTwgSsl_Node_Expression_Binary_Mul extends MulBinary
    {
    }
}
