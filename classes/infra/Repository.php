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

class Repository
{
    public function dataFolder(): string
    {
        global $pth, $plugin_cf;

        $pcf = $plugin_cf['adventcalendar'];

        if ($pcf['folder_data'] == '') {
            $fn = $pth['folder']['plugins'] . 'adventcalendar/data/';
        } else {
            $fn = $pth['folder']['base'] . $pcf['folder_data'];
        }
        if (substr($fn, -1) != '/') {
            $fn .= '/';
        }
        if (!file_exists($fn)) {
            if (mkdir($fn, 0777, true)) {
                chmod($fn, 0777);
            }
        }
        return $fn;
    }

    /** @return list<string> */
    public function findCalendars(): array
    {
        $calendars = [];
        $folder = $this->dataFolder();
        $dir = opendir($folder);
        while (($entry = readdir($dir)) !== false) {
            $name = basename($entry, '.jpg');
            if (pathinfo($folder . $entry, PATHINFO_EXTENSION) == 'jpg'
                && strpos($name, '+') != strlen($name) - 1
            ) {
                $calendars[] = $name;
            }
        }
        closedir($dir);
        return $calendars;
    }

    /** @return array<array{int,int,int,int}> */
    public function findDoors(string $calendarName): ?array
    {
        $filename = $this->dataFolder() . $calendarName . ".dat";
        if (!is_readable($filename)) {
            return null;
        }
        $contents = file_get_contents($filename);
        $doors = unserialize($contents);
        return is_array($doors) ? $doors : null;
    }

    public function findCover(string $calendarName): ?string
    {
        $filename = $this->dataFolder() . $calendarName . "+.jpg";
        return is_file($filename) ? $filename : null;
    }

    /** @return array{int,int,string} */
    public function findImage(string $calendarName): ?array
    {
        $filename = $this->dataFolder() . $calendarName . ".jpg";
        $data = file_get_contents($filename);
        if ($data === false) {
            return null;
        }
        [$width, $height] = getimagesizefromstring($data);
        if ($width === 0 || $height === 0) {
            return null;
        }
        return [$width, $height, $data];
    }

    public function saveCover(string $calendarName, string $data): bool
    {
        $filename = $this->dataFolder() . $calendarName . "+.jpg";
        return (bool) file_put_contents($filename, $data);
    }

    /** @param array<array{int,int,int,int}> $doors */
    public function saveDoors(string $calendarName, array $doors): bool
    {
        $filename = $this->dataFolder() . $calendarName . ".dat";
        return (bool) file_put_contents($filename, serialize($doors));
    }
}
