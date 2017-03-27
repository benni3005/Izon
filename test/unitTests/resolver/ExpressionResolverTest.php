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

namespace derbenni\izon\test\unitTests\resolver;

use \derbenni\izon\Container;
use \derbenni\izon\resolver\ExpressionResolver;
use \derbenni\izon\DependencyException;
use \derbenni\izon\test\TestCase;
use \InvalidArgumentException;
use \RuntimeException;
use \stdClass;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class ExpressionResolverTest extends TestCase {

  /**
   *
   * @covers \derbenni\izon\resolver\ExpressionResolver::can
   */
  public function testCan_ReturnsTrueIfStringGiven() {
    self::assertTrue((new ExpressionResolver())->can('bar'));
  }

  /**
   *
   * @covers \derbenni\izon\resolver\ExpressionResolver::can
   */
  public function testCan_ReturnsFalseIfNoStringGiven() {
    self::assertFalse((new ExpressionResolver())->can(123));
    self::assertFalse((new ExpressionResolver())->can(123.456));
    self::assertFalse((new ExpressionResolver())->can(true));
    self::assertFalse((new ExpressionResolver())->can(null));
    self::assertFalse((new ExpressionResolver())->can([]));
    self::assertFalse((new ExpressionResolver())->can(new stdClass()));
  }

  /**
   *
   * @covers \derbenni\izon\resolver\ExpressionResolver::resolve
   */
  public function testResolve_CanResolveValueIfNoExpressionHadToBeReplaced() {
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

    self::assertEquals('bar', (new ExpressionResolver())->resolve('bar', $container));
  }

  /**
   *
   * @covers \derbenni\izon\resolver\ExpressionResolver::resolve
   * @expectedException InvalidArgumentException
   */
  public function testResolve_CanThrowInvalidArgumentExceptionIfInvalidValueGiven() {
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

    (new ExpressionResolver())->resolve(123, $container);
  }

  /**
   *
   * @covers \derbenni\izon\resolver\ExpressionResolver::resolve
   * @expectedException \derbenni\izon\DependencyException
   */
  public function testResolve_CanThrowDependencyExceptionIfExpressionCouldNotBeResolved() {
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();
    $container
      ->expects(self::once())
      ->method('get')
      ->with(self::equalTo('baz'))
      ->willThrowException(new DependencyException());

    (new ExpressionResolver())->resolve('bar.{baz}', $container);
  }

  /**
   *
   * @covers \derbenni\izon\resolver\ExpressionResolver::resolve
   * @expectedException \RuntimeException
   * @runInSeparateProcess
   */
  public function testResolve_CanThrowRuntimeExceptionIfExpressionCouldNotBeResolved() {
    $pregReplaceCallbackMock = $this->getBuiltInFunctionMock('preg_replace_callback');
    $pregReplaceCallbackMock
      ->expects(self::once())
      ->willReturn(null);

    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

    (new ExpressionResolver())->resolve('bar.{baz}', $container);
  }
}
