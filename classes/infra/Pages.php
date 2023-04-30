<?php

/*
 * Copyright 2012-2023 Christoph M. Becker
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

class Pages
{
    public function findByHeading(string $heading): int
    {
        global $h;

        $index = array_search($heading, $h, true);
        return $index !== false ? $index : -1;
    }

    /** @codeCoverageIgnore */
    public function urlOf(int $index): string
    {
        global $u;

        return $u[$index];
    }

    /** @return list<int> */
    public function childrenOf(int $index): array
    {
        global $cl, $l;

        $children = [];
        $ll = PHP_INT_MAX;
        for ($i = $index + 1; $i < $cl; $i++) {
            if ($l[$i] <= $l[$index]) {
                break;
            }
            if ($l[$i] <= $ll) {
                $children[] = $i;
                $ll = $l[$i];
            }
        }
        return $children;
    }
}
