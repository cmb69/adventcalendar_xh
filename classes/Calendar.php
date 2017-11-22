<?php

/**
 * The calendars.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Adventcalendar
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2012-2017 Christoph M. Becker <http://3-magi.net/>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Adventcalendar_XH
 */

namespace Adventcalendar;

class Calendar
{
    /**
     * @return array<self>
     */
    public static function getAll()
    {
        $result = array();
        $folder = self::dataFolder();
        $dir = opendir($folder);
        while (($entry = readdir($dir)) !== false) {
            $name = basename($entry, '.jpg');
            if (pathinfo($folder . $entry, PATHINFO_EXTENSION) == 'jpg'
                && strpos($name, '+') != strlen($name) - 1
            ) {
                $result[] = new self($name);
            }
        }
        closedir($dir);
        return $result;
    }

    /**
     * @param string $name
     * @return self
     */
    public static function findByName($name)
    {
        return new self($name);
    }

    /**
     * @return string
     */
    protected static function dataFolder()
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
        if (file_exists($fn)) {
            if (!is_dir($fn)) {
                e('cntopen', 'folder', $fn);
            }
        } else {
            if (!mkdir($fn, 0777, true)) {
                e('cntwriteto', 'folder', $fn);
            }
        }
        return $fn;
    }

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array<array<int>>
     */
    protected $doors;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
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
            $filename = self::dataFolder() . $this->name . '.dat';
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
        $filename = self::dataFolder() . $this->name . '.jpg';
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
        $filename = self::dataFolder() . $this->name . '.dat';
        return (bool) file_put_contents($filename, serialize($this->doors));
    }
}
