<?php

use Twig\TemplateWrapper;

class_exists('Twig\TemplateWrapper');

//@trigger_error(sprintf('Using the "Twig_supTwgSsl_TemplateWrapper" class is deprecated since Twig version 2.7, use "Twig\TemplateWrapper" instead.'), E_USER_DEPRECATED);

if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\TemplateWrapper" instead */
    class Twig_supTwgSsl_TemplateWrapper extends TemplateWrapper
    {
    }
}
