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

namespace derbenni\wp\di\test\unitTests\definition\resolver\resolver;

use \derbenni\wp\di\Container;
use \derbenni\wp\di\definition\resolver\parameter\DefaultValueParameterResolver;
use \derbenni\wp\di\test\dummy\ParameterResolverTestDummy;
use \derbenni\wp\di\test\TestCase;
use \ReflectionParameter;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class DefaultValueParameterResolverTest extends TestCase {

  /**
   *
   * @var DefaultValueParameterResolver
   */
  private $sut = null;

  protected function setUp() {
    parent::setUp();

    $this->sut = new DefaultValueParameterResolver();
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\resolver\parameter\DefaultValueParameterResolver::can
   */
  public function testCan_ReturnsTrueIfTheGivenParameterHasDefaultValue() {
    $parameter = new ReflectionParameter([ParameterResolverTestDummy::class, 'defaultValueParameter'], 'foo');

    self::assertTrue($this->sut->can($parameter, []));
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\resolver\parameter\DefaultValueParameterResolver::can
   */
  public function testCan_ReturnsFalseIfTheGivenParameterHasNoDefaultValue() {
    $parameter = new ReflectionParameter([ParameterResolverTestDummy::class, 'unspecifiedParameter'], 'foo');

    self::assertFalse($this->sut->can($parameter, []));
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\resolver\parameter\DefaultValueParameterResolver::resolve
   */
  public function testResolve_CanReturnTheDefaultValueOfTheGivenParameter() {
    $parameter = new ReflectionParameter([ParameterResolverTestDummy::class, 'defaultValueParameter'], 'foo');
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

    self::assertEquals('bar', $this->sut->resolve($parameter, $container, []));
  }
}
