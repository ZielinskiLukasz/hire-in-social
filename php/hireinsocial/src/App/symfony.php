<?php

/*
 * This file is part of the Hire in Social project.
 *
 * (c) Norbert Orzechowicz <norbert@orzechowicz.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

use HireInSocial\Application\Config;
use HireInSocial\Application\System;

function symfony(Config $config, System $system) : SymfonyKernel
{
    $frameworkConfig = [
        'parameters' => [
            'google_recaptcha_secret' => $config->getString(Config::RECAPTCHA_SECRET),
            'apply_email_template' => $config->getString(Config::APPLY_EMAIL_TEMPLATE),
        ],
        'framework' => [
            'secret' => $config->getString(Config::SYMFONY_SECRET),
            'csrf_protection' => null,
            'validation' => [
                'enabled' => true,
                'enable_annotations' => false,
            ],
            'annotations' => [
                'enabled' => false,
            ],
            'session' => [
                'cookie_samesite' => 'strict',
                'save_path' => '/var/lib/php/sessions',
            ],
            'default_locale' => $config->getString(Config::LOCALE),
            'translator' => [
                'fallbacks' => [$config->getString(Config::LOCALE)],
                'paths' => [
                    $config->getString(Config::ROOT_PATH) . '/resources/translations',
                ],
            ],
            'templating' => [
                'engines' => [
                    'twig',
                ],
            ],
        ],
        'twig' => [
            'paths' => [
                $config->getString(Config::ROOT_PATH) . '/resources/templates/' . $config->getString(Config::LOCALE) . '/ui' => '__main__',
            ],
            'date' => [
                'timezone' => $config->getString(Config::TIMEZONE),
            ],
            'cache' => $config->getString(Config::ROOT_PATH) . '/var/cache/' . $config->getString(Config::ENV) . '/twig',
            'globals' => [
                'facebook' => [
                    'app_id' => $config->getString(Config::FB_APP_ID),
                ],
                'google' => [
                    'recaptcha' => [
                        'key' => $config->getString(Config::RECAPTCHA_KEY),
                    ],
                    'maps' => [
                        'key' => $config->getString(Config::GOOGLE_MAPS_KEY),
                    ],
                ],
                'assets' => [
                    'storage_url' => $config->getJson(Config::FILESYSTEM_CONFIG)['storage_url'],
                ],
            ],
            'auto_reload' => $config->getString(Config::ENV) !== 'prod',
        ],
        'monolog' => [
            'handlers' => [
                'file_log' => [
                    'type'  => 'stream',
                    'path'  => '%kernel.logs_dir%/%kernel.environment%_symfony.log',
                    'level' => 'debug',
                    'channels' => [
                        '!event', '!console', '!request', '!security',
                    ],
                ],
            ],
        ],
        'facebook' => [
            'app_id' => $config->getString(Config::FB_APP_ID),
            'app_secret' => $config->getString(Config::FB_APP_SECRET),
        ],
    ];

    if ($config->getString(Config::ENV) === 'test') {
        $frameworkConfig['framework']['test'] = true;
        $frameworkConfig['framework']['session']['storage_id'] = 'session.storage.mock_file';
    }

    return new SymfonyKernel(
        $config->getString(Config::ROOT_PATH),
        $config->getString(Config::ENV),
        $config->getString(Config::ENV) !== 'prod',
        $frameworkConfig,
        $system
    );
}
