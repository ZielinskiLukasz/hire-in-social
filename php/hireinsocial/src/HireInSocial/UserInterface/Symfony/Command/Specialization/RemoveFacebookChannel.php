<?php

declare(strict_types=1);

namespace HireInSocial\UserInterface\Symfony\Command\Specialization;

use HireInSocial\Application\Command\Specialization\RemoveFacebookChannel as SystemRemoveFacebookChannel;
use HireInSocial\Application\Query\Specialization\SpecializationQuery;
use HireInSocial\Application\System;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class RemoveFacebookChannel extends Command
{
    public const NAME = 'specialization:channel:facebook:remove';
    protected static $defaultName = self::NAME;

    private $system;

    public function __construct(System $system)
    {
        parent::__construct();

        $this->system = $system;
    }

    protected function configure() : void
    {
        $this
            ->setDescription('<info>[Specialization]</info> Remove facebook channel from specialization.')
            ->addArgument('slug', InputArgument::REQUIRED, 'Specialization slug')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io = new SymfonyStyle($input, $output);

        $io->note('Create new specialization');

        if ($input->isInteractive()) {
            $answer = $io->ask('Are you sure you want remove facebook channel from the specialization?', 'yes');

            if (\mb_strtolower($answer) !== 'yes') {
                $io->note('Ok, action cancelled.');

                return 1;
            }
        }

        if (!$this->system->query(SpecializationQuery::class)->findBySlug($input->getArgument('slug'))) {
            $io->error(sprintf('Specialization slug "%s" does not exists.', $input->getArgument('slug')));

            return 1;
        }

        try {
            $this->system->handle(new SystemRemoveFacebookChannel(
                $input->getArgument('slug')
            ));
        } catch (\Throwable $e) {
            $io->error('Can\'t remove specialization facebook channel, check logs for more details.');

            return 1;
        }

        $io->success('Specialization facebook channel removed');

        return 0;
    }
}