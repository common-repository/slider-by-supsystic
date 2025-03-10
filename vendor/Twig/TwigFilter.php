<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Twig;

use Twig\Node\Expression\FilterExpression;
use Twig\Node\Node;

/**
 * Represents a template filter.
 *
 * @final since Twig 2.4.0
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @see https://twig.symfony.com/doc/templates.html#filters
 */
class TwigFilter
{
    private $name;
    private $callable;
    private $options;
    private $arguments = [];

    /**
     * Creates a template filter.
     *
     * @param string        $name     Name of this filter
     * @param callable|null $callable A callable implementing the filter. If null, you need to overwrite the "node_class" option to customize compilation.
     * @param array         $options  Options array
     */
    public function __construct(string $name, $callable = null, array $options = [])
    {
        if (__CLASS__ !== \get_class($this)) {
            //@trigger_error('Overriding '.__CLASS__.' is deprecated since Twig 2.4.0 and the class will be final in 3.0.', E_USER_DEPRECATED);
        }

        $this->name = $name;
        $this->callable = $callable;
        $this->options = array_merge([
            'needs_environment' => false,
            'needs_context' => false,
            'is_variadic' => false,
            'is_safe' => null,
            'is_safe_callback' => null,
            'pre_escape' => null,
            'preserves_safety' => null,
            'node_class' => FilterExpression::class,
            'deprecated' => false,
            'alternative' => null,
        ], $options);
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the callable to execute for this filter.
     *
     * @return callable|null
     */
    public function getCallable()
    {
        return $this->callable;
    }

    public function getNodeClass()
    {
        return $this->options['node_class'];
    }

    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    public function needsEnvironment()
    {
        return $this->options['needs_environment'];
    }

    public function needsContext()
    {
        return $this->options['needs_context'];
    }

    public function getSafe(Node $filterArgs)
    {
        if (null !== $this->options['is_safe']) {
            return $this->options['is_safe'];
        }

        if (null !== $this->options['is_safe_callback']) {
            return $this->options['is_safe_callback']($filterArgs);
        }
    }

    public function getPreservesSafety()
    {
        return $this->options['preserves_safety'];
    }

    public function getPreEscape()
    {
        return $this->options['pre_escape'];
    }

    public function isVariadic()
    {
        return $this->options['is_variadic'];
    }

    public function isDeprecated()
    {
        return (bool) $this->options['deprecated'];
    }

    public function getDeprecatedVersion()
    {
        return $this->options['deprecated'];
    }

    public function getAlternative()
    {
        return $this->options['alternative'];
    }
}

// For Twig 1.x compatibility
class_alias('Twig\TwigFilter', 'Twig_supTwgSsl_SimpleFilter', false);

class_alias('Twig\TwigFilter', 'Twig_supTwgSsl_Filter');

// Ensure that the aliased name is loaded to keep BC for classes implementing the typehint with the old aliased name.
class_exists('Twig\Node\Node');
