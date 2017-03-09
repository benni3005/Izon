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

namespace derbenni\wp\di\test\unitTests\definition;

use \derbenni\wp\di\Container;
use \derbenni\wp\di\definition\StringDefinition;
use \derbenni\wp\di\NotFoundException;
use \derbenni\wp\di\test\TestCase;
use \RuntimeException;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class StringDefinitionTest extends TestCase {

  /**
   *
   * @covers \derbenni\wp\di\definition\StringDefinition::getId
   */
  public function testGetId_CanSetAndReturnId() {
    self::assertEquals('foo', (new StringDefinition('foo', 'bar'))->getId());
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\StringDefinition::define
   */
  public function testDefine_CanSetAndDefineValueIfNoExpressionHadToBeReplaced() {
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();
    self::assertEquals('bar', (new StringDefinition('foo', 'bar'))->define($container));
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\StringDefinition::define
   * @expectedException \derbenni\wp\di\DependencyException
   * @expectedExceptionMessage could not be resolved in definition
   */
  public function testDefine_CanThrowDependencyExceptionIfExpressionCouldNotBeResolved() {
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();
    $container
      ->expects(self::once())
      ->method('get')
      ->with(self::equalTo('baz'))
      ->willThrowException(new NotFoundException());

    (new StringDefinition('foo', 'bar.{baz}'))->define($container);
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\StringDefinition::define
   * @expectedException RuntimeException
   * @expectedExceptionMessage Something unforeseen happened
   * @runInSeparateProcess
   */
  public function testDefine_CanThrowRuntimeExceptionIfExpressionCouldNotBeResolved() {
    $pregReplaceCallbackMock = $this->getBuiltInFunctionMock('preg_replace_callback');
    $pregReplaceCallbackMock
      ->expects(self::once())
      ->willReturn(null);

    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

    (new StringDefinition('foo', 'bar.{baz}'))->define($container);
  }
}
