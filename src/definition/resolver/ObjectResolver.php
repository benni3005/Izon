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
use \SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class ObjectResolver implements iResolver {

  /**
   * Checks if the given classname exists.
   *
   * @param string $className
   * @return bool
   *
   * @since 1.0
   */
  public function can($className): bool {
    return (is_string($className) && class_exists($className));
  }

  /**
   * Creates a new instance of the class with the given name and returns it.
   *
   * @param string $className
   * @param Container $container
   * @return mixed
   * @throws InvalidArgumentException If no valid classname was given.
   *
   * @since 1.0
   */
  public function resolve($className, Container $container) {
    if(!$this->can($className)) {
      throw new InvalidArgumentException(sprintf('Given classname "%s" is not a string or does not exist.', print_r($className, true)));
    }
    return new $className;
  }
}
