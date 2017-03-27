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

namespace derbenni\izon\resolver;

use \derbenni\izon\Container;
use \derbenni\izon\DependencyException;
use \derbenni\izon\NotFoundException;
use \InvalidArgumentException;
use \RuntimeException;

/**
 * Resolver for parsing expressions and replacing them with other definition values.
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 *
 * @since 1.0
 */
class ExpressionResolver implements iResolver {

  /**
   * Checks if the given expression is actually a string.
   *
   * @param string $expression
   * @return bool
   *
   * @since 1.0
   */
  public function can($expression): bool {
    return is_string($expression);
  }

  /**
   * Parses the expression and replaces the found matches with other definitions of the container.
   *
   * @param string $expression
   * @param Container $container
   * @return string
   * @throws InvalidArgumentException If no string was given.
   * @throws DependencyException If a parsed expression could not be resolved.
   * @throws RuntimeException If something unforeseen happened during parsing of the expression.
   *
   * @since 1.0
   */
  public function resolve($expression, Container $container) {
    if(!$this->can($expression)) {
      throw new InvalidArgumentException(sprintf('Given expression is not a string. It\'s type is "%s".', gettype($expression)));
    }

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
