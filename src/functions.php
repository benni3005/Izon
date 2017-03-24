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

use \derbenni\wp\di\definition\ArrayDefinition;
use \derbenni\wp\di\definition\EntryReferenceDefinition;
use \derbenni\wp\di\definition\ExpressionDefinition;
use \derbenni\wp\di\definition\factory\ExpressionFactory;
use \derbenni\wp\di\definition\factory\GetFactory;
use \derbenni\wp\di\definition\factory\ObjectFactory;
use \derbenni\wp\di\definition\factory\ValueFactory;
use \derbenni\wp\di\definition\ObjectDefinition;
use \derbenni\wp\di\definition\ScalarDefinition;

if(!function_exists('derbenni\wp\di\value')) {

  /**
   * Helper for defining a scalar or array value.
   *
   * @param mixed $value
   * @return ArrayDefinition|ScalarDefinition
   *
   * @since 1.0
   */
  function value($value) {
    return (new ValueFactory())->make([$value]);
  }
}

if(!function_exists('derbenni\wp\di\expression')) {

  /**
   * Helper for defining an expression.
   *
   * Example:
   *   $definitions = [
   *     'basepath' => derbenni\wp\di\value('/path/to/somewhere'),
   *     'path' => derbenni\wp\di\expression('{basepath}/some-file.txt'),
   *   ];
   *
   * @param string $expression The expression to parse. Use the {} placeholder for referencing other container definitions.
   * @return ExpressionDefinition
   *
   * @since 1.0
   */
  function expression(string $expression) {
    return (new ExpressionFactory())->make([$expression]);
  }
}

if(!function_exists('derbenni\wp\di\object')) {

  /**
   * Helper for creating an object definition.
   *
   * @param string $className The classname of the desired object.
   * @return ObjectDefinition
   *
   * @since 1.0
   */
  function object(string $className) {
    return (new ObjectFactory())->make([$className]);
  }
}

if(!function_exists('derbenni\wp\di\get')) {

  /**
   * Helper for creating a reference to another definition.
   *
   * @param string $id The ID of the other definition.
   * @return EntryReferenceDefinition
   *
   * @since 1.0
   */
  function get(string $id) {
    return (new GetFactory())->make([$id]);
  }
}
