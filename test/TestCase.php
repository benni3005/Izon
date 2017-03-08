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

namespace derbenni\wp\di\test;

use \PHPUnit\Framework\TestCase as OriginalTestCase;
use \ReflectionClass;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
abstract class TestCase extends OriginalTestCase {

  /**
   * Returns the value of a protected/private property of the given object.
   *
   * @param mixed $object
   * @param string $propertyName
   * @return mixed
   */
  public function returnValueOfPrivateProperty($object, string $propertyName) {
    $reflection = new ReflectionClass(get_class($object));
    $property = $reflection->getProperty($propertyName);
    $property->setAccessible(true);

    return $property->getValue($object);
  }
}
