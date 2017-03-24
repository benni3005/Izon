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

namespace derbenni\wp\di\resolver;

use \derbenni\wp\di\Container;

/**
 * Basic interface for definition resolvers.
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 *
 * @since 1.0
 */
interface iResolver {

  /**
   * Checks if the resolver can actually resolve the given value.
   *
   * @param mixed $value
   * @return bool
   *
   * @since 1.0
   */
  public function can($value): bool;

  /**
   * This method will resolve the given value.
   *
   * @param Container $container Used for building other definitions found when resolving.
   * @return mixed
   * @throws InvalidArgumentException If invalid value was given.
   *
   * @since 1.0
   */
  public function resolve($value, Container $container);
}
