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
 * Imports blocks defined in another template into the current template.
 *
 * <pre>
 * {% extends "base.html" %}
 *
 * {% use "blocks.html" %}
 *
 * {% block title %}{% endblock %}
 * {% block content %}{% endblock %}
 * </pre>
 *
 * @see http://www.twig-project.org/doc/templates.html#horizontal-reuse for details.
 *
 * @final
 */
class Twig_supTwgSsl_TokenParser_Use extends Twig_supTwgSsl_TokenParser
{
    public function parse(Twig_supTwgSsl_Token $token)
    {
        $template = $this->parser->getExpressionParser()->parseExpression();
        $stream = $this->parser->getStream();

        if (!$template instanceof Twig_supTwgSsl_Node_Expression_Constant) {
            throw new Twig_supTwgSsl_Error_Syntax('The template references in a "use" statement must be a string.', $stream->getCurrent()->getLine(), $stream->getSourceContext());
        }

        $targets = array();
        if ($stream->nextIf('with')) {
            do {
                $name = $stream->expect(Twig_supTwgSsl_Token::NAME_TYPE)->getValue();

                $alias = $name;
                if ($stream->nextIf('as')) {
                    $alias = $stream->expect(Twig_supTwgSsl_Token::NAME_TYPE)->getValue();
                }

                $targets[$name] = new Twig_supTwgSsl_Node_Expression_Constant($alias, -1);

                if (!$stream->nextIf(Twig_supTwgSsl_Token::PUNCTUATION_TYPE, ',')) {
                    break;
                }
            } while (true);
        }

        $stream->expect(Twig_supTwgSsl_Token::BLOCK_END_TYPE);

        $this->parser->addTrait(new Twig_supTwgSsl_Node(array('template' => $template, 'targets' => new Twig_supTwgSsl_Node($targets))));
    }

    public function getTag()
    {
        return 'use';
    }
}
