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
use \derbenni\wp\di\definition\resolver\parameter\iParameterResolver;
use \InvalidArgumentException;
use \ReflectionMethod;

/**
 * Resolver for class methods, which can fetch dependencies of them.
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 *
 * @since 1.0
 */
class MethodResolver implements iResolver {

  /**
   *
   * @var iParameterResolver
   */
  private $parameterResolver = null;

  /**
   * Sets the parameter resolver needed to inspect each parameter of a method.
   *
   * @param iParameterResolver $parameterResolver
   *
   * @since 1.0
   */
  public function __construct(iParameterResolver $parameterResolver) {
    $this->parameterResolver = $parameterResolver;
  }

  /**
   * Checks if the given method is an instance of ReflectionMethod.
   *
   * @param ReflectionMethod $method
   * @return bool
   *
   * @since 1.0
   */
  public function can($method): bool {
    return ($method instanceof ReflectionMethod);
  }

  /**
   * Checks the parameters of a method and returns its resolved parameters.
   *
   * @param ReflectionMethod $method
   * @param Container $container
   * @param mixed[] $arguments
   * @return mixed
   * @throws InvalidArgumentException If no valid method was given.
   *
   * @since 1.0
   */
  public function resolve($method, Container $container, array $arguments = []) {
    if(!$this->can($method)) {
      throw new InvalidArgumentException(sprintf('Given method is not an instance of ReflectionMethod: %s', print_r($method, true)));
    }

    $resolvedArguments = [];

    foreach($method->getParameters() as $parameter) {
      $resolvedArguments[] = $this->parameterResolver->resolve($parameter, $container, $arguments);
    }
    return $resolvedArguments;
  }
}
