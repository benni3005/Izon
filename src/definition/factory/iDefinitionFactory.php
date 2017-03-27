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

namespace derbenni\izon\definition\factory;

use \derbenni\izon\definition\iDefinition;
use \InvalidArgumentException;

/**
 * A basic interface marking a factory class for creating definitions.
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 *
 * @since 1.0
 */
interface iDefinitionFactory {

  /**
   * Will create a new definition and pass the given parameters to it, if needed.
   *
   * @param array $parameters Contains all needed parameters for passing it to the definition while creating it.
   * @return iDefinition A ready-to-use instance of the definition.
   * @throws InvalidArgumentException Thrown if the passed parameters did notch the definitions' needs.
   *
   * @since 1.0
   */
  public function make(array $parameters = []): iDefinition;
}
