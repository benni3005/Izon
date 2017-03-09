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

use \InvalidArgumentException;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class ValueDefinition implements iDefinition {

  /**
   *
   * @var string
   */
  private $id = '';

  /**
   *
   * @var mixed
   */
  private $value = null;

  /**
   * Sets the ID and value of this definition.
   * Beware: The value can only be a scalar type or an array!
   *
   * @param string $id
   * @param mixed $value
   * @throws InvalidArgumentException If the value given to the definition is not scalar or an array.
   *
   * @since 1.0
   */
  public function __construct(string $id, $value) {
    if(!is_scalar($value) && !is_array($value)) {
      throw new InvalidArgumentException(sprintf('The type for ID "%s" is neither scalar nor an array. It\'s of the type "%s".', $id, gettype($value)));
    }

    $this->id = $id;
    $this->value = $value;
  }

  /**
   * Returns the ID of this definition.
   *
   * @return string
   *
   * @since 1.0
   */
  public function getId(): string {
    return $this->id;
  }

  /**
   * Returns the value of this definition.
   *
   * @return mixed
   *
   * @since 1.0
   */
  public function define() {
    return $this->value;
  }
}
