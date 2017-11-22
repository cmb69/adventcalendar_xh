<?php

/**
 * The images.
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
