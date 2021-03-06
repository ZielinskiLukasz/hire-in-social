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

namespace HireInSocial\Application\Command\Offer;

use HireInSocial\Application\Command\ClassCommand;
use HireInSocial\Application\System\Command;

final class RemoveOffer implements Command
{
    use ClassCommand;

    private $offerId;
    private $userId;

    public function __construct(string $offerId, string $userId)
    {
        $this->offerId = $offerId;
        $this->userId = $userId;
    }

    public function offerId(): string
    {
        return $this->offerId;
    }

    public function userId(): string
    {
        return $this->userId;
    }
}
