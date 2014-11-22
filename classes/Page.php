<?php

/**
 * The pages.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Adventcalendar
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2012-2014 Christoph M. Becker <http://3-magi.net/>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version   SVN: $Id$
 * @link      http://3-magi.net/?CMSimple_XH/Adventcalendar_XH
 */

/**
 * The pages.
 *
 * @category CMSimple_XH
 * @package  Adventcalendar
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Adventcalendar_XH
 */
class Adventcalendar_Page
{
    /**
     * Returns the first page with a certain heading,
     * <var>null</var> if no such page exists.
     *
     * @param string $heading A heading.
     *
     * @return Adventcalendar_Page
     *
     * @global array The headings of the pages.
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
     * The page index.
     *
     * @var int
     */
    protected $index;

    /**
     * Initializes a new instance.
     *
     * @param int $index A page index.
     */
    protected function __construct($index)
    {
        $this->index = $index;
    }

    /**
     * Returns the page URL.
     *
     * @return string
     *
     * @global array The URLs of the pages.
     */
    public function getURL()
    {
        global $u;

        return $u[$this->index];
    }

    /**
     * Returns an array of the direct child pages.
     *
     * @return array<self>
     *
     * @global int   The number of pages.
     * @global array The levels of the pages.
     * @global array The configuration of the core.
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

?>
