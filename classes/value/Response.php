<?php

/*
 * Copyright 2023 Christoph M. Becker
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

namespace Adventcalendar\Value;

class Response
{
    public static function create(string $output = ""): self
    {
        $that = new self;
        $that->output = $output;
        return $that;
    }

    public static function redirect(string $location): self
    {
        $that = new self;
        $that->location = $location;
        return $that;
    }

    /** @var string */
    private $output;

    /** @var bool */
    private $javascript = false;

    /** @var string|null */
    private $location = null;

    public function withJavascript(): self
    {
        $that = clone $this;
        $that->javascript = true;
        return $that;
    }

    public function output(): string
    {
        return $this->output;
    }

    public function javascript(): bool
    {
        return $this->javascript;
    }

    public function location(): ?string
    {
        return $this->location;
    }
}
