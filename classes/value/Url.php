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

class Url
{
    /** @var string */
    private $baseAbs;

    /** @var string */
    private $baseRel;

    /** @var string */
    private $page;

    /** @var array<string,string|array<string>> */
    private $params;

    public function __construct(string $baseAbs, string $baseRel, string $page, string $query)
    {
        $this->baseAbs = $baseAbs;
        $this->baseRel = $baseRel;
        $this->page = $page;
        $query = preg_replace('/^[^=]*(&|$)/', "", $query);
        parse_str($query, $this->params);
    }

    public function withPage(string $page): self
    {
        $that = clone $this;
        $that->page = $page;
        $that->params = [];
        return $that;
    }

    public function withParam(string $key, string $value = ""): self
    {
        $that = clone $this;
        $that->params[$key] = $value;
        return $that;
    }

    /** @return string,string|array<string>|null */
    public function param(string $key)
    {
        return $this->params[$key];
    }

    public function relative(): string
    {
        $result = $this->baseRel;
        $queryString = $this->queryString();
        if ($queryString) {
            $result .= "?$queryString";
        }
        return $result;
    }

    public function absolute(): string
    {
        $result = $this->baseAbs;
        $queryString = $this->queryString();
        if ($queryString) {
            $result .= "?$queryString";
        }
        return $result;
    }

    private function queryString(): string
    {
        $result = $this->page;
        $additional = preg_replace('/=(?=&|$)/', "", http_build_query($this->params, "", "&"));
        if ($additional) {
            $result .= "&$additional";
        }
        return $result;
    }
}
