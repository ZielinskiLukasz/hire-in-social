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

namespace HireInSocial\Application\Offer;

use HireInSocial\Application\User\User;
use Ramsey\Uuid\UuidInterface;

interface Offers
{
    public function add(Offer $offer) : void;
    public function getById(UuidInterface $offerId) : Offer;
    public function postedBy(User $user, \DateTimeImmutable $since) : UserOffers;
}
