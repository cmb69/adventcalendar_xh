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

namespace Adventcalendar;

class Image
{
    /**
     * @var resource
     */
    protected $image;

    /**
     * @param resource $image
     */
    public function __construct($image)
    {
        $this->image = $image;
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
    protected function drawStamp($x1, $y1, $x2, $y2)
    {
        global $plugin_cf;

        $color = $this->allocateColor($plugin_cf['adventcalendar']['color_door']);
        imagerectangle($this->image, $x1, $y1, $x2, $y2, $color);
    }

    /**
     * @param int $x
     * @param int $y
     * @param int $number
     * @return void
     */
    protected function drawNumber($x, $y, $number)
    {
        global $plugin_cf;

        $this->drawFringe($x, $y, $number);
        $color = $this->allocateColor($plugin_cf['adventcalendar']['color_font']);
        imagestring($this->image, 5, $x, $y, $number, $color);
    }

    /**
     * @param int $x
     * @param int $y
     * @param int $number
     * @return void
     */
    protected function drawFringe($x, $y, $number)
    {
        global $plugin_cf;

        for ($i = $x - 1; $i <= $x + 1; $i++) {
            for ($j = $y - 1; $j <= $y + 1; $j++) {
                $color = $this->allocateColor(
                    $plugin_cf['adventcalendar']['color_fringe']
                );
                imagestring($this->image, 5, $i, $j, $number, $color);
            }
        }
    }

    /**
     * @param string $hexcolor
     * @return int
     */
    protected function allocateColor($hexcolor)
    {
        $color = base_convert($hexcolor, 16, 10);
        $red = $color >> 16;
        $green = ($color & 0xffff) >> 8;
        $blue = $color & 0xff;
        return imagecolorallocate($this->image, $red, $green, $blue);
    }
}
