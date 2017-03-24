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

/**
 * Definition for a callable, that can build anything to be returned by the Container.
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 *
 * @since 1.0
 */
class FactoryDefinition implements iDefinition {

  /**
   *
   * @var callable
   */
  private $factory = null;

  /**
   * Sets the callable to return the value of the definition.
   *
   * @param string $factory
   *
   * @since 1.0
   */
  public function __construct(callable $factory) {
    $this->factory = $factory;
  }

  /**
   * Returns the value returned by the factory.
   * Will pass the container itself to the factory callable.
   *
   * @param Container $container Used for replacing expressions in the form of "{foo.bar-baz}".
   * @return mixed The return value of the factory callable.
   *
   * @since 1.0
   */
  public function define(Container $container) {
    return call_user_func_array($this->factory, [$container]);
  }
}
