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

namespace derbenni\izon\definition;

use \derbenni\izon\Container;
use \derbenni\izon\resolver\iMethodResolver;
use \derbenni\izon\resolver\iObjectResolver;
use \ReflectionMethod;

/**
 * A definition for creating instances of objects.
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 *
 * @since 1.0
 */
class ObjectDefinition implements iDefinition {

  /**
   *
   * @var string
   */
  private $className = '';

  /**
   *
   * @var iObjectResolver
   */
  private $objectResolver = null;

  /**
   *
   * @var iMethodResolver
   */
  private $methodResolver = null;

  /**
   *
   * @var array
   */
  private $properties = [];

  /**
   *
   * @var array
   */
  private $constructor = [];

  /**
   *
   * @var array
   */
  private $methods = [];

  /**
   * Sets the classname of the object and a resolver.
   *
   * @param string $className
   * @param iObjectResolver $objectResolver
   * @param iMethodResolver $methodResolver
   *
   * @since 1.0
   */
  public function __construct(string $className, iObjectResolver $objectResolver, iMethodResolver $methodResolver) {
    $this->className = $className;
    $this->objectResolver = $objectResolver;
    $this->methodResolver = $methodResolver;
  }

  /**
   * Defines a property to set within the resolved object.
   *
   * Multiple calls with the same property name will overwrite the value of it.
   *
   * @param string $property The name of the property.
   * @param mixed $value The value of the property.
   * @return self
   *
   * @since 1.0
   */
  public function property(string $property, $value): ObjectDefinition {
    $this->properties[$property] = $value;
    return $this;
  }

  /**
   * Defines which parameters to pass to the constructor of the object to resolve.
   *
   * A variable number of arguments can be passed to this method.
   *
   * Example:
   *
   * $definitions = [
   *   'SomeClass' => derbenni\izon\object('SomeClass')->constructor($param1, $param2),
   * ];
   *
   * @param mixed ... The parameters to pass to the constructor when resolving the object.
   * @return self
   *
   * @since 1.0
   */
  public function constructor(): ObjectDefinition {
    $this->constructor = func_get_args();
    return $this;
  }

  /**
   * Defines a specific parameter's value to pass to the objects constructor when resolving it.
   *
   * Use this method when autowiring does not work. This can happen if a scalar value is
   * expected by the constructor of the object or it isn't type hinted at all.
   *
   * When using this method the parameters not defined with it will still be autowired. When
   * using it together with constructor() the parameter defined here will be used instead of
   * the other one, since named parameters are prioritized higher when resolving method parameters.
   *
   * @param string $parameter
   * @param mixed $value
   * @return self
   *
   * @since 1.0
   */
  public function constructorParameter(string $parameter, $value): ObjectDefinition {
    $this->constructor[$parameter] = $value;
    return $this;
  }

  /**
   * Defines which parameters to pass to the given method of the object to resolve.
   *
   * A variable number of arguments can be passed to this method after the method name.
   *
   * Can be used multiple times if the method should be called more than once with different parameters.
   *
   * If the method name passed is "__construct" then it will set the parameters for the constructor
   * and overwrite all existing parameters defined for it.
   *
   * Example:
   *
   * $definitions = [
   *   'SomeClass' => derbenni\izon\object('SomeClass')->method('someMethod', $param1, $param2),
   * ];
   *
   * @param string $method The method to call when resolving the object.
   * @param mixed ... The parameters to pass to the method when resolving the object.
   * @return self
   *
   * @since 1.0
   */
  public function method(string $method): ObjectDefinition {
    if($method === '__construct') {
      $this->constructor = array_slice(func_get_args(), 1);
      return $this;
    }

    if(!array_key_exists($method, $this->methods)) {
      $this->methods[$method] = [];
    }

    $this->methods[$method][] = array_slice(func_get_args(), 1);
    return $this;
  }

  /**
   * Defines a specific method to call and the parameter's value to pass to it when resolving the object.
   *
   * Use this method when autowiring does not work. This can happen if a scalar value is
   * expected by the method of the object or it isn't type hinted at all.
   *
   * If the method name passed is "__construct" then it will set the parameter for the constructor
   * and overwrite the one defined for it previously.
   *
   * When using this method the parameters not defined with it will still be autowired. Be aware of
   * this method only setting parameters for the first call of the passed method. Also, if you already
   * defined parameters for a method by calling method() the named parameter will be used when resolving
   * the object, since named parameters are prioritized higher when resolving method parameters.
   *
   * @param string $method
   * @param string $parameter
   * @param mixed $value
   * @return self
   *
   * @since 1.0
   */
  public function methodParameter(string $method, string $parameter, $value): ObjectDefinition {
    if($method === '__construct') {
      $this->constructor[$parameter] = $value;
      return $this;
    }

    if(!array_key_exists($method, $this->methods)) {
      $this->methods[$method] = [0 => []];
    }

    $this->methods[$method][0][$parameter] = $value;
    return $this;
  }

  /**
   * Returns an instance of the object.
   *
   * If other dependencies are found in the constructor they will be automatically resolved, if possible.
   * If parameters were previously defined for the constructor or other methods they will be passed/called, too.
   *
   * @param Container $container Used for resolving other dependencies found in the objects constructor.
   * @return mixed The object instance.
   *
   * @since 1.0
   */
  public function define(Container $container) {
    $instance = $this->objectResolver->resolve($this->className, $container, $this->constructor);

    foreach($this->properties as $property => $value) {
      $reflectionProperty = new \ReflectionProperty($instance, $property);

      if(!$reflectionProperty->isPublic()) {
        $reflectionProperty->setAccessible(true);
      }

      $reflectionProperty->setValue($instance, $value);
    }

    foreach($this->methods as $method => $callArguments) {
      foreach($callArguments as $arguments) {
        $reflectionMethod = new ReflectionMethod($instance, $method);
        $resolvedArguments = $this->methodResolver->resolve($reflectionMethod, $container, $arguments);

        $reflectionMethod->invokeArgs($instance, $resolvedArguments);
      }
    }
    return $instance;
  }
}
