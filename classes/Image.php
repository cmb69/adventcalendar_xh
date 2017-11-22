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

/**
 * The images.
 *
 * @category CMSimple_XH
 * @package  Adventcalendar
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Adventcalendar_XH
 */
class Adventcalendar_Image
{
    /**
     * The image.
     *
     * @var resource
     */
    protected $image;

    /**
     * Initializes a new instance.
     *
     * @param resource $image A GD image.
     */
    public function __construct($image)
    {
        $this->image = $image;
    }

    /**
     * Draws the doors.
     *
     * @param array<array<int>> $doors An array of door coordinates.
     *
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
     * Draws the stamp.
     *
     * @param int $x1 An x-coordinate.
     * @param int $y1 An y-coordinate.
     * @param int $x2 An x-coordinate.
     * @param int $y2 An y-coordinate.
     *
     * @return void
     *
     * @global array The configuration of the plugins.
     */
    protected function drawStamp($x1, $y1, $x2, $y2)
    {
        global $plugin_cf;

        imagerectangle(
            $this->image, $x1, $y1, $x2, $y2,
            $this->allocateColor($plugin_cf['adventcalendar']['color_door'])
        );
    }

    /**
     * Draws the number.
     *
     * @param int $x      An x-coordinate.
     * @param int $y      An y-coordinate.
     * @param int $number A day number.
     *
     * @return void
     *
     * @global array The configuration of the plugins.
     */
    protected function drawNumber($x, $y, $number)
    {
        global $plugin_cf;

        $this->drawFringe($x, $y, $number);
        imagestring(
            $this->image, 5, $x, $y, $number,
            $this->allocateColor($plugin_cf['adventcalendar']['color_font'])
        );
    }

    /**
     * Draws the fringe.
     *
     * @param int $x      An x-coordinate.
     * @param int $y      An y-coordinate.
     * @param int $number A day number.
     *
     * @return void
     *
     * @global array The configuration of the plugins.
     */
    protected function drawFringe($x, $y, $number)
    {
        global $plugin_cf;

        for ($i = $x - 1; $i <= $x + 1; $i++) {
            for ($j = $y - 1; $j <= $y + 1; $j++) {
                imagestring(
                    $this->image, 5, $i, $j, $number,
                    $this->allocateColor(
                        $plugin_cf['adventcalendar']['color_fringe']
                    )
                );
            }
        }
    }

    /**
     * Allocates and returns a color.
     *
     * @param string $hexcolor A 24-bit hexadecimal RGB value.
     *
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

?>
