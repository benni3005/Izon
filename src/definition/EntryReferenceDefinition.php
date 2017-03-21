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
 * Definition for referencing other entries in the container.
 * This can be useful in array definitions when trying to get some other defined entries.
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class EntryReferenceDefinition implements iDefinition {

  /**
   *
   * @var string
   */
  private $id = '';

  /**
   * Sets the ID of the definition to reference.
   *
   * @param string $id
   *
   * @since 1.0
   */
  public function __construct(string $id) {
    $this->id = $id;
  }

  /**
   * Returns the referenced definition by using the ID given in the constructor.
   *
   * @param Container $container Used for building other definitions found when resolving.
   * @return mixed
   *
   * @since 1.0
   */
  public function define(Container $container) {
    return $container->get($this->id);
  }
}
