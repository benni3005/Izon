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

use \derbenni\wp\di\Container;
use \derbenni\wp\di\DependencyException;
use \Interop\Container\Exception\NotFoundException;
use \RuntimeException;

/**
 * A simple definition, whcih makes it possible to parse strings for expressions and matching them against other definitions.
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class StringDefinition implements iDefinition {

  /**
   *
   * @var string
   */
  private $id = '';

  /**
   *
   * @var string
   */
  private $string = '';

  /**
   * Sets the ID and string to parse of this definition.
   *
   * @param string $id
   * @param string $string
   *
   * @since 1.0
   */
  public function __construct(string $id, string $string) {
    $this->id = $id;
    $this->string = $string;
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
   * Returns the parsed string of this definition.
   *
   * @param Container $container Used for replacing expressions in the form of "{foo.bar-baz}".
   * @return string The parsed string.
   * @throws DependencyException If an expression could not be resolved.
   * @throws RuntimeException If something completely unforeseen happened.
   *
   * @since 1.0
   */
  public function define(Container $container) {
    $id = $this->id;
    $string = $this->string;

    $result = preg_replace_callback('#\{([^\{\}]+)\}#', function (array $matches) use ($container, $id) {
      try {
        return $container->get($matches[1]);
      }catch(NotFoundException $exception) {
        throw new DependencyException(sprintf('Expression "%s" could not be resolved in definition with ID "%s"', $matches[1], $id), 0, $exception);
      }
    }, $string);

    if($result === null) {
      throw new RuntimeException(sprintf('Something unforeseen happened when parsing the expressions for definition with ID "%s"', $id));
    }

    return $result;
  }
}
