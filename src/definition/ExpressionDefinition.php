<?php

declare(strict_types = 1);

/**
 * WP-DI: A lightweight dependency injection container for WordPress.
 * Copyright (C) 2017 Benjamin Hofmann
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software Foundation,
 * Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301  USA
 */

namespace derbenni\wp\di\definition;

use \derbenni\wp\di\Container;
use \derbenni\wp\di\DependencyException;
use \Interop\Container\Exception\NotFoundException;
use \RuntimeException;

/**
 * A simple definition, which makes it possible to parse strings for expressions and matching them against other definitions.
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 *
 * @since 1.0
 */
class ExpressionDefinition implements iDefinition {

  /**
   *
   * @var string
   */
  private $expression = '';

  /**
   * Sets the expression to parse.
   *
   * @param string $expression
   *
   * @since 1.0
   */
  public function __construct(string $expression) {
    $this->expression = $expression;
  }

  /**
   * Returns the parsed string of this definition.
   *
   * @param Container $container Used for replacing expressions in the form of "{foo.bar-baz}".
   * @return string The parsed string.
   * @throws DependencyException If an expression could not be resolved.
   * @throws RuntimeException If something completely unforeseen happened.
   *
   * @since 1.0
   */
  public function define(Container $container) {
    $expression = $this->expression;

    $result = preg_replace_callback('#\{([^\{\}]+)\}#', function (array $matches) use ($container, $expression) {
      try {
        return $container->get($matches[1]);
      }catch(NotFoundException $exception) {
        throw new DependencyException(sprintf('Expression "%s" could not be resolved in "%s"', $matches[1], $expression), 0, $exception);
      }
    }, $expression);

    if($result === null) {
      throw new RuntimeException(sprintf('Something unforeseen happened when parsing the expression "%s"', $expression));
    }

    return $result;
  }
}
