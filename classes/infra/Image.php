<?php

/*
 * Copyright 2012-2017 Christoph M. Becker
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

use GdImage;

class Image
{
    /** @var GdImage */
    private $image;

    /** @var string */
    private $doorColor;

    /** @var string */
    private $fontColor;

    /** @var string */
    private $fringeColor;

    /** @param GdImage $image */
    public function __construct($image, string $doorColor, string $fontColor, string $fringeColor)
    {
        $this->image = $image;
        $this->doorColor = $doorColor;
        $this->fontColor = $fontColor;
        $this->fringeColor = $fringeColor;
    }

    /**
     * @param array<array<int>> $doors
     * @return void
     */
    public function drawDoors($doors)
    {
        for ($i = 0; $i < 24; $i++) {
            list($x1, $y1, $x2, $y2) = $doors[$i];
            $this->drawStamp($x1, $y1, $x2, $y2);
            $this->drawNumber($x1 + 2, $y1 + 1, $i + 1);
        }
    }

    /**
     * @param int $x1
     * @param int $y1
     * @param int $x2
     * @param int $y2
     * @return void
     */
    private function drawStamp($x1, $y1, $x2, $y2)
    {
        $color = $this->allocateColor($this->doorColor);
        imagerectangle($this->image, $x1, $y1, $x2, $y2, $color);
    }

    /**
     * @param int $x
     * @param int $y
     * @param int $number
     * @return void
     */
    private function drawNumber($x, $y, $number)
    {
        $this->drawFringe($x, $y, $number);
        $color = $this->allocateColor($this->fontColor);
        imagestring($this->image, 5, $x, $y, (string) $number, $color);
    }

    /**
     * @param int $x
     * @param int $y
     * @param int $number
     * @return void
     */
    private function drawFringe($x, $y, $number)
    {
        for ($i = $x - 1; $i <= $x + 1; $i++) {
            for ($j = $y - 1; $j <= $y + 1; $j++) {
                $color = $this->allocateColor($this->fringeColor);
                imagestring($this->image, 5, $i, $j, (string) $number, $color);
            }
        }
    }

    /**
     * @param string $hexcolor
     * @return int
     */
    private function allocateColor($hexcolor)
    {
        $color = (int) base_convert($hexcolor, 16, 10);
        $red = $color >> 16;
        $green = ($color & 0xffff) >> 8;
        $blue = $color & 0xff;
        return imagecolorallocate($this->image, $red, $green, $blue);
    }
}