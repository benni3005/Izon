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

use \derbenni\izon\definition\factory\iObjectDefinitionFactory;
use \derbenni\izon\definition\iDefinition;
use \Interop\Container\ContainerInterface;

/**
 * Dependency Injection Container.
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 *
 * @since 1.0
 */
class Container implements ContainerInterface {

  /**
   *
   * @var iObjectFactory
   */
  private $objectFactory = null;

  /**
   *
   * @var iDefinition[]
   */
  private $definitions = [];

  /**
   *
   * @var mixed[]
   */
  private $cache = [];

  /**
   * Sets the definitions known to this instance of the container.
   *
   * @param iObjectFactory $objectFactory The object factory needed for autowiring objects.
   * @param iDefinition[] $definitions The configured definitions of the container to resolve later.
   *
   * @since 1.0
   */
  public function __construct(iObjectDefinitionFactory $objectFactory, array $definitions) {
    $this->objectFactory = $objectFactory;

    foreach($definitions as $id => $definition) {
      $this->add($id, $definition);
    }
  }

  /**
   * Adds a definition to the container.
   *
   * @param string $name
   * @param iDefinition $definition
   * @return self
   *
   * @since 1.0
   */
  public function add(string $name, iDefinition $definition): Container {
    $this->definitions[$name] = $definition;
    return $this;
  }

  /**
   * Returns the value of a definition by using its ID as key.
   * If it was requested before it will get cached for further requests. If you need the value to be built every time use ::make instead.
   *
   * @param string $name
   * @return mixed
   * @throws NotFoundException If no definition could be found for the given ID.
   *
   * @since 1.0
   */
  public function get($name) {
    if(!$this->has($name)) {
      throw new NotFoundException(sprintf('ID "%s" was not found in the container.', $name));
    }

    if(!array_key_exists($name, $this->cache)) {
      $this->cache[$name] = $this->definitions[$name]->define($this);
    }

    return $this->cache[$name];
  }

  /**
   * Returns the value of a definition by using its ID as key.
   * This method will always rebuild the value and not use caching.
   *
   * @param string $name
   * @return mixed
   * @throws NotFoundException If no definition could be found for the given ID.
   *
   * @since 1.0
   */
  public function make($name) {
    if(!$this->has($name)) {
      throw new NotFoundException(sprintf('ID "%s" was not found in the container.', $name));
    }
    return $this->definitions[$name]->define($this);
  }

  /**
   * Returns whether there is any entry in the definitions for the given ID.
   *
   * @param string $name
   * @return bool
   *
   * @since 1.0
   */
  public function has($name): bool {
    $exists = array_key_exists($name, $this->definitions);

    if(!$exists && class_exists($name)) {
      $this->add($name, $this->objectFactory->make([$name]));
      return true;
    }
    return $exists;
  }
}
