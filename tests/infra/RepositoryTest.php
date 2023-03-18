<?php

/*
 * Copyright 2023 Christoph M. Becker
 *
 * This file is part of Adventcalendar_XH.
 *
 * Adventcalendar_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Adventcalendar_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Adventcalendar_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Adventcalendar\Infra;

use Adventcalendar\Logic\Util;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class RepositoryTest extends TestCase
{
    public function testCreatesDataFolder(): void
    {
        vfsStream::setup("root");
        $sut = new Repository("vfs://root/repo/");
        $folder = $sut->dataFolder();
        $this->assertEquals("vfs://root/repo/", $folder);
        $this->assertFileExists("vfs://root/repo/");
    }

    public function testFindsCalendars(): void
    {
        vfsStream::setup("root");
        mkdir("vfs://root/repo/", 0777, true);
        touch("vfs://root/repo/foo.jpg");
        touch("vfs://root/repo/bar.txt");
        touch("vfs://root/repo/baz+.jpg");
        touch("vfs://root/repo/qux.jpg");
        $sut = new Repository("vfs://root/repo/");
        $calendars = $sut->findCalendars();
        $this->assertEquals(["foo", "qux"], $calendars);
    }

    public function testFindsImageUrl(): void
    {
        vfsStream::setup("root");
        mkdir("vfs://root/repo/");
        touch("vfs://root/repo/test.jpg");
        $sut = new Repository("vfs://root/repo/");
        $url = $sut->findImageUrl("test");
        $this->assertEquals("vfs://root/repo/test.jpg", $url);
    }

    public function testReturnsNullOnMissingImage(): void
    {
        vfsStream::setup("root");
        $sut = new Repository("vfs://root/repo/");
        $url = $sut->findImageUrl("test");
        $this->assertNull($url);
    }

    public function testFindsDoors(): void
    {
        vfsStream::setup("root");
        $doors = Util::calculateDoors(400, 300, 50, 50);
        $sut = new Repository("vfs://root/repo/");
        $sut->saveDoors("test", $doors);
        $actual = $sut->findDoors("test");
        $this->assertEquals($doors, $actual);
    }

    public function testReturnsNullOnMissingDoors(): void
    {
        vfsStream::setup("root");
        $sut = new Repository("vfs://root/repo/");
        $doors = $sut->findDoors("test");
        $this->assertNull($doors);
    }

    public function testFindsCover(): void
    {
        vfsStream::setup("root");
        $sut = new Repository("vfs://root/repo/");
        $sut->saveCover("test", "irrelevant data");
        $cover = $sut->findCover("test");
        $this->assertEquals("vfs://root/repo/test+.jpg", $cover);
    }

    public function testFindsImage(): void
    {
        vfsStream::setup("root");
        mkdir("vfs://root/repo/", 0777, true);
        $im = imagecreatetruecolor(17, 4);
        imagejpeg($im, "vfs://root/repo/test.jpg");
        $sut = new Repository("vfs://root/repo/");
        [$width, $height, $data] = $sut->findImage("test");
        $this->assertEquals(17, $width);
        $this->assertEquals(4, $height);
        $this->assertStringEqualsFile("vfs://root/repo/test.jpg", $data);
    }

    public function testCannotFindImage(): void
    {
        vfsStream::setup("root");
        $sut = new Repository("vfs://root/repo/");
        $image = $sut->findImage("test");
        $this->assertNull($image);
    }

    public function testFindsInvalidImage(): void
    {
        vfsStream::setup("root");
        mkdir("vfs://root/repo/", 0777, true);
        file_put_contents("vfs://root/repo/test.jpg", "spme non image data");
        $sut = new Repository("vfs://root/repo/");
        $image = $sut->findImage("test");
        $this->assertNull($image);
    }
}
