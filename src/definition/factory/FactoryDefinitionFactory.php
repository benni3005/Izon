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

namespace derbenni\izon\definition\factory;

use \derbenni\izon\definition\FactoryDefinition;
use \derbenni\izon\definition\iDefinition;
use \InvalidArgumentException;

/**
 * Factory for creatin factory definitions.
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 *
 * @since 1.0
 */
class FactoryDefinitionFactory implements iDefinitionFactory {

  /**
   * Will create a new factory definition.
   *
   * @param array $parameters Only the first parameter will be taken into account for passing it to the definition.
   * @return iDefinition A ready-to-use instance of the definition.
   * @throws InvalidArgumentException Thrown if the first given parameter is not a callable.
   *
   * @since 1.0
   */
  public function make(array $parameters = []): iDefinition {
    $factory = reset($parameters);

    if(!is_callable($factory)) {
      throw new InvalidArgumentException(vsprintf('The given factory is not a callable. It\'s type is "%s".', [
        is_object($factory) ? get_class($factory) : gettype($factory),
      ]));
    }

    return new FactoryDefinition($factory);
  }
}
