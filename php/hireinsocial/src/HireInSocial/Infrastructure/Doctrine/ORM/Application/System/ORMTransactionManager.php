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

namespace HireInSocial\Infrastructure\Doctrine\ORM\Application\System;

use Doctrine\ORM\EntityManagerInterface;
use HireInSocial\Application\System\TransactionManager;

final class ORMTransactionManager implements TransactionManager
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function begin() : void
    {
        $this->entityManager->beginTransaction();
    }

    public function commit() : void
    {
        $this->entityManager->flush();
        $this->entityManager->commit();
    }

    public function rollback() : void
    {
        $this->entityManager->rollback();
    }
}
