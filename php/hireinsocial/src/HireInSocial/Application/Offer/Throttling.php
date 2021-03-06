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

use HireInSocial\Application\System\Calendar;
use HireInSocial\Application\User\User;

final class Throttling
{
    public const LIMIT = 2;
    public const SINCE = 'P7D';

    private $limit;
    private $since;
    private $calendar;

    public function __construct(int $defaultLimit, \DateInterval $since, Calendar $calendar)
    {
        $this->limit = $defaultLimit;
        $this->since = $since;
        $this->calendar = $calendar;
    }

    public static function createDefault(Calendar $calendar) : self
    {
        return new Throttling(
            self::LIMIT,
            new \DateInterval(self::SINCE),
            $calendar
        );
    }

    public function limit(): int
    {
        return $this->limit;
    }

    public function since(): \DateInterval
    {
        return $this->since;
    }

    public function isThrottled(User $user, Offers $offers) : bool
    {
        if ($offers->postedBy($user, $this->calendar->currentTime()->sub($this->since))->count() >= $this->limit) {
            return true;
        }

        return false;
    }
}
