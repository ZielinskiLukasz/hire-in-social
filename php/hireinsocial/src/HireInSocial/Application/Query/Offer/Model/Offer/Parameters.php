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

namespace HireInSocial\Application\Query\Offer\Model\Offer;

final class Parameters
{
    private $company;
    private $contact;
    private $contract;
    private $description;
    private $location;
    private $position;
    private $salary;

    public function __construct(
        Company $company,
        Contact $contact,
        Contract $contract,
        Description $description,
        Location $location,
        Position $position,
        ?Salary $salary
    ) {
        $this->company = $company;
        $this->contact = $contact;
        $this->contract = $contract;
        $this->description = $description;
        $this->location = $location;
        $this->position = $position;
        $this->salary = $salary;
    }

    public function company(): Company
    {
        return $this->company;
    }

    public function contact(): Contact
    {
        return $this->contact;
    }

    public function contract(): Contract
    {
        return $this->contract;
    }

    public function description(): Description
    {
        return $this->description;
    }

    public function location(): Location
    {
        return $this->location;
    }

    public function position(): Position
    {
        return $this->position;
    }

    public function salary(): ?Salary
    {
        return $this->salary;
    }
}
