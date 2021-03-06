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

class Calendar
{
    /**
     * @param string $dataFolder
     * @return array<self>
     */
    public static function getAll($dataFolder)
    {
        $result = array();
        $folder = $dataFolder;
        $dir = opendir($folder);
        while (($entry = readdir($dir)) !== false) {
            $name = basename($entry, '.jpg');
            if (pathinfo($folder . $entry, PATHINFO_EXTENSION) == 'jpg'
                && strpos($name, '+') != strlen($name) - 1
            ) {
                $result[] = new self($name, $dataFolder);
            }
        }
        closedir($dir);
        return $result;
    }

    /**
     * @param string $name
     * @param string $dataFolder
     * @return self
     */
    public static function findByName($name, $dataFolder)
    {
        return new self($name, $dataFolder);
    }

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $dataFolder;

    /**
     * @var array<array<int>>
     */
    private $doors;

    /**
     * @param string $name
     * @param string $dataFolder
     */
    public function __construct($name, $dataFolder)
    {
        $this->name = $name;
        $this->dataFolder = (string) $dataFolder;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array<array<int>>
     */
    public function getDoors()
    {
        if (!isset($this->doors)) {
            $filename = $this->dataFolder . $this->name . '.dat';
            if (!is_readable($filename)) {
                return null;
            }
            $contents = file_get_contents($filename);
            $this->doors = unserialize($contents);
        }
        return $this->doors;
    }

    /**
     * @return resource
     */
    public function getImage()
    {
        $filename = $this->dataFolder . $this->name . '.jpg';
        $image = imagecreatefromjpeg($filename);
        return $image ? $image : null;
    }

    /**
     * @param int $width
     * @param int $height
     * @return void
     */
    public function calculateDoors($width, $height)
    {
        global $plugin_cf;

        if ($width >= $height) {
            $doorsPerRow = 6;
            $doorsPerCol = 4;
        } else {
            $doorsPerRow = 4;
            $doorsPerCol = 6;
        }
        $dw = $plugin_cf['adventcalendar']['door_width'];
        $dh = $plugin_cf['adventcalendar']['door_height'];
        $dx = ($width - $doorsPerRow * $dw) / ($doorsPerRow + 1);
        $dy = ($height - $doorsPerCol * $dh) / ($doorsPerCol + 1);
        $this->doors = array();
        for ($i = 0; $i < $doorsPerRow; $i++) {
            $x1 = ($i + 1) * $dx + $i * $dw;
            $x2 = $x1 + $dw;
            for ($j = 0; $j < $doorsPerCol; $j++) {
                $y1 = ($j + 1) * $dy + $j * $dh;
                $y2 = $y1 + $dh;
                $this->doors[] = array(
                    round($x1), round($y1), round($x2), round($y2)
                );
            }
        }
        shuffle($this->doors);
    }

    /**
     * @return bool
     */
    public function save()
    {
        $filename = $this->dataFolder . $this->name . '.dat';
        return (bool) file_put_contents($filename, serialize($this->doors));
    }
}
