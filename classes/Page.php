<?php

/**
 * The pages.
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
    protected $index;

    /**
     * @param int $index
     */
    protected function __construct($index)
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
