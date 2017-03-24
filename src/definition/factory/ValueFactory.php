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

use \derbenni\wp\di\definition\ArrayDefinition;
use \derbenni\wp\di\definition\iDefinition;
use \derbenni\wp\di\definition\resolver\ArrayResolver;
use \derbenni\wp\di\definition\ScalarDefinition;
use \InvalidArgumentException;

/**
 * Factory for building definitions for scalar and array values.
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 *
 * @since 1.0
 */
class ValueFactory implements iFactory {

  /**
   * Will create a new scalar or array definition, based on the given value.
   *
   * @param array $parameters Only the first parameter will be taken into account for passing it to the definition.
   * @return iDefinition A ready-to-use instance of the definition.
   * @throws InvalidArgumentException Thrown if neither an array nor scalar value were passed as first value in the parameters.
   *
   * @since 1.0
   */
  public function make(array $parameters = []): iDefinition {
    $value = reset($parameters);

    if(!is_array($value) && !is_scalar($value)) {
      throw new InvalidArgumentException(vsprintf('The given value is neither scalar nor an array. It\'s type is "%s".', [
        is_object($value) ? get_class($value) : gettype($value),
      ]));
    }

    if(is_array($value)) {
      return new ArrayDefinition($value, new ArrayResolver());
    }
    return new ScalarDefinition($value);
  }
}
