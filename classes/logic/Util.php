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

namespace Adventcalendar\Logic;

class Util
{
    /** @return list<array{int,int,int,int}> */
    public static function calculateDoors(int $width, int $height, int $doorWidth, int $doorHeight)
    {
        if ($width >= $height) {
            $doorsPerRow = 6;
            $doorsPerCol = 4;
        } else {
            $doorsPerRow = 4;
            $doorsPerCol = 6;
        }
        $dw = $doorWidth;
        $dh = $doorHeight;
        $dx = ($width - $doorsPerRow * $dw) / ($doorsPerRow + 1);
        $dy = ($height - $doorsPerCol * $dh) / ($doorsPerCol + 1);
        $doors = [];
        for ($i = 0; $i < $doorsPerRow; $i++) {
            $x1 = ($i + 1) * $dx + $i * $dw;
            $x2 = $x1 + $dw;
            for ($j = 0; $j < $doorsPerCol; $j++) {
                $y1 = ($j + 1) * $dy + $j * $dh;
                $y2 = $y1 + $dh;
                $doors[] = [(int) round($x1), (int) round($y1), (int) round($x2), (int) round($y2)];
            }
        }
        return $doors;
    }
}
