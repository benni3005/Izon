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

namespace derbenni\wp\di\definition;

/**
 * The basic interface for a definition.
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
interface iDefinition {

  /**
   * Returns the ID of the definition.
   *
   * @return string
   *
   * @since 1.0.0
   */
  public function getId(): string;

  /**
   * Returns the value of the definition. This can be of every type in PHP you can imagine.
   *
   * @return mixed
   *
   * @since 1.0.0
   */
  public function getValue();
}
