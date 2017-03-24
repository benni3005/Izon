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
use \derbenni\wp\di\resolver\iResolver;

/**
 * A definition for creating instances of objects.
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class ObjectDefinition implements iDefinition {

  /**
   *
   * @var string
   */
  private $className = '';

  /**
   *
   * @var iResolver
   */
  private $resolver = null;

  /**
   * Sets the classname of the object and a resolver.
   *
   * @param string $className
   * @param iResolver $resolver
   *
   * @since 1.0
   */
  public function __construct(string $className, iResolver $resolver) {
    $this->className = $className;
    $this->resolver = $resolver;
  }

  /**
   * Returns an instance of the object.
   *
   * If other dependencies are found in the constructor they will be automatically resolved, if possible.
   * This can happen for other objects pretty easily, for anything different an exception gets thrown.
   *
   * @param Container $container Used for resolving other dependencies found in the objects constructor.
   * @return mixed The object instance.
   *
   * @since 1.0
   */
  public function define(Container $container) {
    return $this->resolver->resolve($this->className, $container);
  }
}
