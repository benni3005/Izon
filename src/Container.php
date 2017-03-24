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

namespace derbenni\wp\di;

use \derbenni\wp\di\definition\factory\iObjectDefinitionFactory;
use \derbenni\wp\di\definition\iDefinition;
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
   * @param string $id
   * @param iDefinition $definition
   * @return self
   *
   * @since 1.0
   */
  public function add(string $id, iDefinition $definition): Container {
    $this->definitions[$id] = $definition;
    return $this;
  }

  /**
   * Returns the value of a definition by using its ID as key.
   * If it was requested before it will get cached for further requests. If you need the value to be built every time use ::make instead.
   *
   * @param string $id
   * @return mixed
   * @throws NotFoundException If no definition could be found for the given ID.
   *
   * @since 1.0
   */
  public function get($id) {
    if(!$this->has($id)) {
      throw new NotFoundException(sprintf('ID "%s" was not found in the container.', $id));
    }

    if(!array_key_exists($id, $this->cache)) {
      $this->cache[$id] = $this->definitions[$id]->define($this);
    }

    return $this->cache[$id];
  }

  /**
   * Returns the value of a definition by using its ID as key.
   * This method will always rebuild the value and not use caching.
   *
   * @param string $id
   * @return mixed
   * @throws NotFoundException If no definition could be found for the given ID.
   *
   * @since 1.0
   */
  public function make($id) {
    if(!$this->has($id)) {
      throw new NotFoundException(sprintf('ID "%s" was not found in the container.', $id));
    }
    return $this->definitions[$id]->define($this);
  }

  /**
   * Returns whether there is any entry in the definitions for the given ID.
   *
   * @param string $id
   * @return bool
   *
   * @since 1.0
   */
  public function has($id): bool {
    $exists = array_key_exists($id, $this->definitions);

    if(!$exists && class_exists($id)) {
      $this->add($id, $this->objectFactory->make([$id]));
      return true;
    }
    return $exists;
  }
}
