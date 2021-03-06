<?php

declare(strict_types=1);

/*
 * This file is part of the Hire in Social project.
 *
 * (c) Norbert Orzechowicz <norbert@orzechowicz.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HireInSocial\Application\Command\Offer\Offer;

final class Company
{
    private $name;
    private $url;
    private $description;

    public function __construct(string $name, string $url, string $description)
    {
        $this->name = $name;
        $this->url = $url;
        $this->description = $description;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function description(): string
    {
        return $this->description;
    }
}
