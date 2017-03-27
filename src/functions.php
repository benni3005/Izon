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

use \derbenni\izon\definition\ArrayDefinition;
use \derbenni\izon\definition\EntryReferenceDefinition;
use \derbenni\izon\definition\ExpressionDefinition;
use \derbenni\izon\definition\FactoryDefinition;
use \derbenni\izon\definition\factory\ExpressionDefinitionFactory;
use \derbenni\izon\definition\factory\FactoryDefinitionFactory;
use \derbenni\izon\definition\factory\GetDefinitionFactory;
use \derbenni\izon\definition\factory\ObjectDefinitionFactory;
use \derbenni\izon\definition\factory\ValueDefinitionFactory;
use \derbenni\izon\definition\ObjectDefinition;
use \derbenni\izon\definition\ScalarDefinition;

if(!function_exists('derbenni\izon\value')) {

  /**
   * Helper for defining a scalar or array value.
   *
   * @param mixed $value
   * @return ArrayDefinition|ScalarDefinition
   *
   * @since 1.0
   */
  function value($value) {
    return (new ValueDefinitionFactory())->make([$value]);
  }
}

if(!function_exists('derbenni\izon\expression')) {

  /**
   * Helper for defining an expression.
   *
   * Example:
   *
   * $definitions = [
   *   'basepath' => derbenni\izon\value('/path/to/somewhere'),
   *   'path' => derbenni\izon\expression('{basepath}/some-file.txt'),
   * ];
   *
   * @param string $expression The expression to parse. Use the {} placeholder for referencing other container definitions.
   * @return ExpressionDefinition
   *
   * @since 1.0
   */
  function expression(string $expression) {
    return (new ExpressionDefinitionFactory())->make([$expression]);
  }
}

if(!function_exists('derbenni\izon\object')) {

  /**
   * Helper for creating an object definition.
   *
   * @param string $className The classname of the desired object.
   * @return ObjectDefinition
   *
   * @since 1.0
   */
  function object(string $className) {
    return (new ObjectDefinitionFactory())->make([$className]);
  }
}

if(!function_exists('derbenni\izon\factory')) {

  /**
   * Helper for creating a factory definition.
   *
   * @param callable $factory The callable to create the value of the definition.
   * @return FactoryDefinition
   *
   * @since 1.0
   */
  function factory(callable $factory) {
    return (new FactoryDefinitionFactory())->make([$factory]);
  }
}

if(!function_exists('derbenni\izon\get')) {

  /**
   * Helper for creating a reference to another definition.
   *
   * @param string $name The ID of the other definition.
   * @return EntryReferenceDefinition
   *
   * @since 1.0
   */
  function get(string $name) {
    return (new GetDefinitionFactory())->make([$name]);
  }
}
