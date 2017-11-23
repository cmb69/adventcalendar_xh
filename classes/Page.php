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

class Page
{
    /**
     * @param string $heading
     * @return ?Page
     */
    public static function getByHeading($heading)
    {
        global $h;

        $index = array_search($heading, $h);
        if ($index !== false) {
            return new self($index);
        } else {
            return null;
        }
    }

    /**
     * @var int
     */
    private $index;

    /**
     * @param int $index
     */
    private function __construct($index)
    {
        $this->index = $index;
    }

    /**
     * @return string
     */
    public function getURL()
    {
        global $u;

        return $u[$this->index];
    }

    /**
     * @return array<self>
     */
    public function getChildren()
    {
        global $cl, $l, $cf;

        $children = array();
        $ll = $cf['menu']['levelcatch'];
        for ($i = $this->index + 1; $i < $cl; $i++) {
            if ($l[$i] <= $l[$this->index]) {
                break;
            }
            if ($l[$i] <= $ll) {
                $children[] = new self($i);
                $ll = $l[$i];
            }
        }
        return $children;
    }
}
