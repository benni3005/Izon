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

namespace derbenni\wp\di\definition\factory;

use \derbenni\wp\di\definition\ExpressionDefinition;
use \derbenni\wp\di\definition\iDefinition;
use \derbenni\wp\di\resolver\ExpressionResolver;
use \InvalidArgumentException;

/**
 * Class used for creating an expression definition.
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 *
 * @since 1.0
 */
class ExpressionDefinitionFactory implements iDefinitionFactory {

  /**
   * Will create a new expression definition.
   *
   * @param array $parameters Only the first parameter will be taken into account for passing it to the definition.
   * @return iDefinition A ready-to-use instance of the definition.
   * @throws InvalidArgumentException Thrown if the first given parameter is not a string.
   *
   * @since 1.0
   */
  public function make(array $parameters = []): iDefinition {
    $expression = reset($parameters);

    if(!is_string($expression)) {
      throw new InvalidArgumentException(vsprintf('The given expression is not a string. It\'s type is "%s".', [
        is_object($expression) ? get_class($expression) : gettype($expression),
      ]));
    }

    return new ExpressionDefinition($expression, new ExpressionResolver());
  }
}
