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

namespace derbenni\izon\test\unitTests;

use \derbenni\izon\Container;
use \derbenni\izon\ContainerBuilder;
use \derbenni\izon\definition\ExpressionDefinition;
use \derbenni\izon\test\TestCase;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class ContainerBuilderTest extends TestCase {

  /**
   *
   * @covers \derbenni\izon\ContainerBuilder::__construct
   */
  public function testConstruct_CanSetDefaultContainerClass() {
    $sut = new ContainerBuilder();

    self::assertEquals(Container::class, $this->returnValueOfPrivateProperty($sut, 'containerClass'));
  }

  /**
   *
   * @covers \derbenni\izon\ContainerBuilder::__construct
   */
  public function testConstruct_CanSetGivenContainerClass() {
    $sut = new ContainerBuilder('OtherContainer');

    self::assertEquals('OtherContainer', $this->returnValueOfPrivateProperty($sut, 'containerClass'));
  }

  /**
   *
   * @covers \derbenni\izon\ContainerBuilder::addDefinitionsByPath
   */
  public function testAddDefinitionsByPath_CanReadAndSaveDefinitionsFound() {
    $sut = new ContainerBuilder();
    $sut->addDefinitionsByPath(__DIR__ . '/../dummy/config/*.php');

    $definitions = $this->returnValueOfPrivateProperty($sut, 'definitions');

    self::assertNotEmpty($definitions);
    self::assertInstanceOf(ExpressionDefinition::class, $definitions['foo']);
  }

  /**
   *
   * @covers \derbenni\izon\ContainerBuilder::build
   */
  public function testBuild_CanBuildContainerWithDefinitions() {
    $sut = new ContainerBuilder();
    $sut->addDefinitionsByPath(__DIR__ . '/../dummy/config/*.php');

    $container = $sut->build();
    $definitions = $this->returnValueOfPrivateProperty($sut, 'definitions');

    self::assertInstanceOf(Container::class, $container);

    self::assertNotEmpty($definitions);
    self::assertInstanceOf(ExpressionDefinition::class, $definitions['foo']);
  }
}
