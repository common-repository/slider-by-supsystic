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
 * Embeds a template.
 *
 * @final
 */
class Twig_supTwgSsl_TokenParser_Embed extends Twig_supTwgSsl_TokenParser_Include
{
    public function parse(Twig_supTwgSsl_Token $token)
    {
        $stream = $this->parser->getStream();

        $parent = $this->parser->getExpressionParser()->parseExpression();

        list($variables, $only, $ignoreMissing) = $this->parseArguments();

        $parentToken = $fakeParentToken = new Twig_supTwgSsl_Token(Twig_supTwgSsl_Token::STRING_TYPE, '__parent__', $token->getLine());
        if ($parent instanceof Twig_supTwgSsl_Node_Expression_Constant) {
            $parentToken = new Twig_supTwgSsl_Token(Twig_supTwgSsl_Token::STRING_TYPE, $parent->getAttribute('value'), $token->getLine());
        } elseif ($parent instanceof Twig_supTwgSsl_Node_Expression_Name) {
            $parentToken = new Twig_supTwgSsl_Token(Twig_supTwgSsl_Token::NAME_TYPE, $parent->getAttribute('name'), $token->getLine());
        }

        // inject a fake parent to make the parent() function work
        $stream->injectTokens(array(
            new Twig_supTwgSsl_Token(Twig_supTwgSsl_Token::BLOCK_START_TYPE, '', $token->getLine()),
            new Twig_supTwgSsl_Token(Twig_supTwgSsl_Token::NAME_TYPE, 'extends', $token->getLine()),
            $parentToken,
            new Twig_supTwgSsl_Token(Twig_supTwgSsl_Token::BLOCK_END_TYPE, '', $token->getLine()),
        ));

        $module = $this->parser->parse($stream, array($this, 'decideBlockEnd'), true);

        // override the parent with the correct one
        if ($fakeParentToken === $parentToken) {
            $module->setNode('parent', $parent);
        }

        $this->parser->embedTemplate($module);

        $stream->expect(Twig_supTwgSsl_Token::BLOCK_END_TYPE);

        return new Twig_supTwgSsl_Node_Embed($module->getTemplateName(), $module->getAttribute('index'), $variables, $only, $ignoreMissing, $token->getLine(), $this->getTag());
    }

    public function decideBlockEnd(Twig_supTwgSsl_Token $token)
    {
        return $token->test('endembed');
    }

    public function getTag()
    {
        return 'embed';
    }
}
