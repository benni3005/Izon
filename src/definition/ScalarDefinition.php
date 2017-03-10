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
use \InvalidArgumentException;

/**
 * A simple definition for setting scalar values.
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 *
 * @since 1.0
 */
class ScalarDefinition implements iDefinition {

  /**
   *
   * @var mixed
   */
  private $scalar = null;

  /**
   * Sets the scalar value of this definition.
   *
   * @param mixed $scalar Can only be a scalar type!
   * @throws InvalidArgumentException If the value given to the definition is not scalar or an array.
   *
   * @since 1.0
   */
  public function __construct($scalar) {
    if(!is_scalar($scalar)) {
      throw new InvalidArgumentException(sprintf('The value given is not scalar. It\'s of the type "%s".', gettype($scalar)));
    }

    $this->scalar = $scalar;
  }

  /**
   * Returns the value of this definition.
   *
   * @param Container $container Not used here, since the value will be returned directly.
   * @return mixed
   *
   * @since 1.0
   */
  public function define(Container $container) {
    return $this->scalar;
  }
}
