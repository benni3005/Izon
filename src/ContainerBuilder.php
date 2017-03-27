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

namespace derbenni\izon;

use \derbenni\izon\definition\factory\ObjectDefinitionFactory;
use \derbenni\izon\definition\iDefinition;

/**
 * Helper to create and configure a Container.
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 *
 * @since 1.0
 */
class ContainerBuilder {

  /**
   *
   * @var string
   */
  private $containerClass = '';

  /**
   *
   * @var iDefinition[]
   */
  private $definitions = [];

  /**
   * Sets the name of the container class to use for resolving dependencies.
   *
   * @param string $containerClass
   *
   * @since 1.0
   */
  public function __construct(string $containerClass = Container::class) {
    $this->containerClass = $containerClass;
  }

  /**
   * Will load all files found by using `glob()` with the given path and saves the definitions within them for building the container later.
   * The config files have to return an array containing the definitions.
   *
   * Example:
   *
   * return [
   *   'basepath' => derbenni\izon\value('/path/to/somewhere'),
   *   'path' => derbenni\izon\expression('{basepath}/some-file.txt'),
   * ];
   *
   * @param string $path
   * @return self
   *
   * @since 1.0
   */
  public function addDefinitionsByPath(string $path): ContainerBuilder {
    foreach(glob($path, GLOB_BRACE) as $configFile) {
      $this->definitions = array_merge($this->definitions, include $configFile);
    }
    return $this;
  }

  /**
   * Builds the container and adds all definitions found within previously set configuration files.
   *
   * @return Container
   *
   * @since 1.0
   */
  public function build(): Container {
    return new $this->containerClass(new ObjectDefinitionFactory, $this->definitions);
  }
}
