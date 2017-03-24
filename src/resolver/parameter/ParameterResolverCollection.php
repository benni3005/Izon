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

namespace derbenni\wp\di\resolver\parameter;

use \derbenni\wp\di\Container;
use \derbenni\wp\di\DependencyException;
use \ReflectionParameter;

/**
 * A collection of parameter resolvers for iterating through them.
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 *
 * @since 1.0
 */
class ParameterResolverCollection implements iParameterResolver {

  /**
   *
   * @var iParameterResolver[]
   */
  private $parameterResolvers = [];

  /**
   * Sets the parameter resolvers to iterate through.
   *
   * @param iParameterResolver[] $parameterResolvers
   *
   * @since 1.0
   */
  public function __construct(array $parameterResolvers) {
    foreach($parameterResolvers as $parameterResolver) {
      $this->add($parameterResolver);
    }
  }

  /**
   * Adds a parameter resolver to the collection.
   *
   * @param iParameterResolver $parameterResolver
   * @return self
   *
   * @since 1.0
   */
  public function add(iParameterResolver $parameterResolver) {
    $this->parameterResolvers[] = $parameterResolver;
  }

  /**
   * Always returns TRUE, since the collection could not be iterated otherwise.
   *
   * @param ReflectionParameter $parameter The parameter to check itself.
   * @param mixed[] $arguments The configured arguments for all parameters of a method.
   * @return bool
   *
   * @since 1.0
   */
  public function can(ReflectionParameter $parameter, array $arguments = []): bool {
    return true;
  }

  /**
   * Iterates over all parameter resolvers wrapped and if one of them can resolve the parameter its return value will be returned.
   *
   * @param ReflectionParameter $parameter The parameter to resolve itself.
   * @param Container $container Used for building other definitions found when resolving.
   * @param mixed[] $arguments The configured arguments for all parameters of a method.
   * @return mixed
   * @throws DependencyException If no parameter resolver in the collection could resolve the given parameter.
   *
   * @since 1.0
   */
  public function resolve(ReflectionParameter $parameter, Container $container, array $arguments = []) {
    foreach($this->parameterResolvers as $parameterResolver) {
      if($parameterResolver->can($parameter, $arguments)) {
        return $parameterResolver->resolve($parameter, $container, $arguments);
      }
    }

    throw new DependencyException(vsprintf('Parameter "%s" of method "%s" in class "%s" could not be resolved.', [
      $parameter->getName(),
      $parameter->getDeclaringFunction()->getName(),
      $parameter->getDeclaringClass()->getName(),
    ]));
  }
}
