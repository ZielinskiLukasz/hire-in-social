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

namespace App\Command\Specialization;

use HireInSocial\Application\Command\Specialization\SetFacebookChannel as SystemSetFacebookChannel;
use HireInSocial\Application\Query\Specialization\SpecializationQuery;
use HireInSocial\Application\System;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class SetFacebookChannel extends Command
{
    public const NAME = 'specialization:channel:facebook:set';
    protected static $defaultName = self::NAME;

    private $system;
    /**
     * @var SymfonyStyle
     */
    private $io;

    public function __construct(System $system)
    {
        parent::__construct();

        $this->system = $system;
    }

    protected function configure() : void
    {
        $this
            ->setDescription('<info>[Specialization]</info> Set facebook channel for specialization.')
            ->addArgument('slug', InputArgument::REQUIRED, 'Specialization slug')
            ->addArgument('facebook_page_id', InputArgument::REQUIRED, 'Facebook page id that will post offers into group')
            ->addArgument('facebook_page_token', InputArgument::REQUIRED, 'Facebook page id access token with publish_to_groups permission')
            ->addArgument('facebook_group_id', InputArgument::REQUIRED, 'Facebook group id where page will post offers')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $this->io->note('Create new specialization');

        if ($input->isInteractive()) {
            $answer = $this->io->ask('Are you sure you want set facebook channel to the specialization?', 'yes');

            if (\mb_strtolower($answer) !== 'yes') {
                $this->io->note('Ok, action cancelled.');

                return 1;
            }
        }

        if (!$this->system->query(SpecializationQuery::class)->findBySlug($input->getArgument('slug'))) {
            $this->io->error(sprintf('Specialization slug "%s" does not exists.', $input->getArgument('slug')));

            return 1;
        }

        try {
            $this->system->handle(new SystemSetFacebookChannel(
                $input->getArgument('slug'),
                $input->getArgument('facebook_page_id'),
                $input->getArgument('facebook_page_token'),
                $input->getArgument('facebook_group_id')
            ));
        } catch (\Throwable $e) {
            $this->io->error('Can\'t set specialization facebook channel, check logs for more details.');

            return 1;
        }

        $this->io->success('Facebook channel added to the specialization');

        return 0;
    }
}
