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

use \derbenni\wp\di\definition\ExpressionDefinition;
use \derbenni\wp\di\definition\ScalarDefinition;

if(!function_exists('derbenni\wp\di\scalar')) {

  /**
   * Helper for defining a scalar or array value.
   *
   * @param mixed $scalar
   * @return ScalarDefinition
   * 
   * @since 1.0
   */
  function scalar($scalar) {
    return new ScalarDefinition($scalar);
  }
}

if(!function_exists('derbenni\wp\di\expression')) {

  /**
   * Helper for defining an expression.
   *
   * Example:
   *  'path' => derbenni\wp\di\expression('{basepath}/some-file.txt')
   *
   * @param string $expression The expression to parse. Use the {} placeholder for referencing other container definitions.
   * @return ExpressionDefinition
   *
   * @since 1.0
   */
  function expression(string $expression) {
    return new ExpressionDefinition($expression);
  }
}
