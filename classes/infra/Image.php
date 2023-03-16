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
    /** @var string */
    private $doorColor;

    /** @var string */
    private $fontColor;

    /** @var string */
    private $fringeColor;

    public function __construct(string $doorColor, string $fontColor, string $fringeColor)
    {
        $this->doorColor = $doorColor;
        $this->fontColor = $fontColor;
        $this->fringeColor = $fringeColor;
    }

    /**
     * @param array<array<int>> $doors
     * @return array{string,array<array{int,int,int,int}>}
     */
    public function drawDoors(string $data, $doors)
    {
        $doors = $this->shuffleDoors($doors);
        $image = imagecreatefromstring($data);
        for ($i = 0; $i < 24; $i++) {
            list($x1, $y1, $x2, $y2) = $doors[$i];
            $this->drawStamp($image, $x1, $y1, $x2, $y2);
            $this->drawNumber($image, $x1 + 2, $y1 + 1, $i + 1);
        }
        return [$this->data($image), $doors];
    }

    /**
     * @param GdImage $image
     * @codeCoverageIgnore
     */
    protected function data($image): string
    {
        ob_start();
        imagejpeg($image);
        return ob_get_clean();
    }

    /**
     * @param array<array{int,int,int,int}> $doors
     * @return array<array{int,int,int,int}>
     * @codeCoverageIgnore
     */
    protected function shuffleDoors(array $doors): array
    {
        shuffle($doors);
        return $doors;
    }

    /**
     * @param GdImage $image
     * @return void
     */
    private function drawStamp($image, int $x1, int $y1, int $x2, int $y2)
    {
        $color = $this->allocateColor($image, $this->doorColor);
        imagerectangle($image, $x1, $y1, $x2, $y2, $color);
    }

    /**
     * @param GdImage $image
     * @return void
     */
    private function drawNumber($image, int $x, int $y, int $number)
    {
        $this->drawFringe($image, $x, $y, $number);
        $color = $this->allocateColor($image, $this->fontColor);
        imagestring($image, 5, $x, $y, (string) $number, $color);
    }

    /**
     * @param GdImage $image
     * @return void
     */
    private function drawFringe($image, int $x, int $y, int $number)
    {
        $color = $this->allocateColor($image, $this->fringeColor);
        for ($i = $x - 1; $i <= $x + 1; $i++) {
            for ($j = $y - 1; $j <= $y + 1; $j++) {
                imagestring($image, 5, $i, $j, (string) $number, $color);
            }
        }
    }

    /** @param GdImage $image */
    private function allocateColor($image, string $hexcolor): int
    {
        $color = (int) base_convert($hexcolor, 16, 10);
        $red = $color >> 16;
        $green = ($color & 0xffff) >> 8;
        $blue = $color & 0xff;
        return imagecolorallocate($image, $red, $green, $blue);
    }
}
