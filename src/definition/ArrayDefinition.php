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
use \derbenni\izon\resolver\iResolver;

/**
 * A definition used for defining arrays and resolving their entries against other definitions.
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 *
 * @since 1.0
 */
class ArrayDefinition implements iDefinition {

  /**
   *
   * @var mixed[]
   */
  private $array = [];

  /**
   *
   * @var iResolver
   */
  private $resolver = null;

  /**
   * Sets the array used in this definition.
   *
   * @param mixed[] $array
   * @param iResolver $resolver
   *
   * @since 1.0
   */
  public function __construct(array $array, iResolver $resolver) {
    $this->array = $array;
    $this->resolver = $resolver;
  }

  /**
   * Returns the array of this definition.
   * If any other definition is found within it will get resolved beforehand.
   *
   * @param Container $container
   * @return mixed[]
   *
   * @since 1.0
   */
  public function define(Container $container): array {
    return $this->resolver->resolve($this->array, $container);
  }
}
