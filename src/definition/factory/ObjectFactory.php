<?php

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

use \derbenni\wp\di\definition\iDefinition;
use \derbenni\wp\di\definition\ObjectDefinition;
use \derbenni\wp\di\definition\resolver\MethodResolver;
use \derbenni\wp\di\definition\resolver\ObjectResolver;
use \derbenni\wp\di\definition\resolver\parameter\ClassNameParameterResolver;
use \derbenni\wp\di\definition\resolver\parameter\ConfiguredIndexedParameterResolver;
use \derbenni\wp\di\definition\resolver\parameter\ConfiguredNamedParameterResolver;
use \derbenni\wp\di\definition\resolver\parameter\DefaultValueParameterResolver;
use \derbenni\wp\di\definition\resolver\parameter\ParameterResolverCollection;
use \InvalidArgumentException;

/**
 * Factory for creating object definitions.
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 *
 * @since 1.0
 */
class ObjectFactory implements iObjectFactory {

  /**
   * Will create a new object definition.
   *
   * @param array $parameters Only the first parameter will be taken into account for passing it to the definition.
   * @return iDefinition A ready-to-use instance of the definition.
   * @throws InvalidArgumentException Thrown if the first given parameter is not a string or the name of a known class.
   *
   * @since 1.0
   */
  public function make(array $parameters = []): iDefinition {
    $className = reset($parameters);

    if(!is_string($className)) {
      throw new InvalidArgumentException(vsprintf('The given classname is not a string. It\'s type is "%s".', [
        is_object($className) ? get_class($className) : gettype($className),
      ]));
    }

    if(!class_exists($className)) {
      throw new InvalidArgumentException(sprintf('The given classname "%s" is unknown.', $className));
    }

    return new ObjectDefinition($className, new ObjectResolver(new MethodResolver(new ParameterResolverCollection([
      new ConfiguredNamedParameterResolver(),
      new ConfiguredIndexedParameterResolver(),
      new ClassNameParameterResolver(),
      new DefaultValueParameterResolver(),
    ]))));
  }
}
