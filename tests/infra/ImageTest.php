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
use ApprovalTests\Approvals;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    public function testIt(): void
    {
        $sut = $this->sut();
        $sut->method("shuffleDoors")->willReturnArgument(0);
        $sut->method("data")->willReturnCallback(function ($image) {
            ob_start();
            imagegif($image);
            return ob_get_clean();
        });
        $im = imagecreatetruecolor(400, 300);
        imagefilledrectangle($im, 0, 0, imagesx($im), imagesy($im), 0x7f7f7f);
        ob_start();
        imagejpeg($im);
        $data = ob_get_clean();
        $doors = Util::calculateDoors(400, 300, 50, 50);
        [$newdata, $newdoors] = $sut->drawDoors($data, $doors);
        $this->assertEquals($doors, $newdoors);
        Approvals::verifyStringWithFileExtension($newdata, "gif");
    }

    private function sut()
    {
        return $this->getMockBuilder(Image::class)
        ->setConstructorArgs(["0000ff", "ff0000", "00ff00"])
        ->disableOriginalClone()
        ->disableArgumentCloning()
        ->disallowMockingUnknownTypes()
        ->onlyMethods(["data", "shuffleDoors"])
        ->getMock();
    }
}
