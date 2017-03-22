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

namespace derbenni\wp\di\definition\resolver;

use \derbenni\wp\di\Container;
use \derbenni\wp\di\definition\iDefinition;
use \derbenni\wp\di\DependencyException;
use \Exception;
use \InvalidArgumentException;
use \ReflectionParameter;

/**
 * Resolver for method parameters, which can resolve a single parameter and return the needed value for it.
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 *
 * @since 1.0
 */
class ParameterResolver implements iResolver {

  /**
   * Checks if the given parameter is an instance of ReflectionParameter.
   *
   * @param ReflectionParameter $parameter
   * @return bool
   *
   * @since 1.0
   */
  public function can($parameter): bool {
    return ($parameter instanceof ReflectionParameter);
  }

  /**
   * Checks the parameter and resolves it.
   *
   * @param ReflectionParameter $parameter
   * @param Container $container
   * @param mixed[] $arguments
   * @return mixed
   * @throws InvalidArgumentException If no valid parameter was given.
   *
   * @since 1.0
   */
  public function resolve($parameter, Container $container, array $arguments = []) {
    if(!$this->can($parameter)) {
      throw new InvalidArgumentException(sprintf('Given parameter is not an instance of ReflectionParameter: %s', print_r($parameter, true)));
    }

    $result = null;

    if(array_key_exists($parameter->getName(), $arguments)) {
      $result = $arguments[$parameter->getName()];
    }elseif(array_key_exists($parameter->getPosition(), $arguments)) {
      $result = $arguments[$parameter->getPosition()];
    }elseif($parameter->getClass()) {
      try {
        $result = $container->get($parameter->getClass()->getName());
      }catch(Exception $exception) {
        if($parameter->isOptional()) {
          $result = $parameter->getDefaultValue();
        }else {
          throw new DependencyException(sprintf('Dependency of class "%s" could not be resolved.', $parameter->getClass()->getName()), 0, $exception);
        }
      }
    }elseif($parameter->isDefaultValueAvailable()) {
      $result = $parameter->getDefaultValue();
    }else {
      throw new DependencyException(vsprintf('Parameter "%s" of method "%s" in class "%s" could not be resolved.', [
        $parameter->getName(),
        $parameter->getDeclaringFunction()->getName(),
        $parameter->getDeclaringClass()->getName(),
      ]));
    }

    if($result instanceof iDefinition) {
      $result = $result->define($container);
    }
    return $result;
  }
}
