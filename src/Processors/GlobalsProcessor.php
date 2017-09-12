<?php
/*
 * This file is part of the Monolog Helper package.
 *
 * (c) Phoenix Osiris <phoenix@twistersfury.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwistersFury\Monolog\Processors;

/**
 * Allows you to easily add php globals to logging.
 *
 * @package TwistersFury\Monolog\Processors
 */
class GlobalsProcessor
{
    /**
     * @var array List of globals to map to record.
     */
    protected $mappedGlobals = [
        'session' => true,
        'get'     => true,
        'post'    => true,
        'request' => true,
        'server'  => true,
        'files'   => true,
        'cookies' => true,
        'env'     => true
    ];

    public function __construct(array $mappedGlobals = null)
    {
        if ($mappedGlobals !== null) {
            $this->mappedGlobals = $mappedGlobals;
        }
    }

    public function __invoke(array $record): array
    {
        $record['php_globals'] = [];

        foreach ($this->mappedGlobals as $storedName => $canMap) {
            if (!$canMap) {
                continue;
            }

            $globalName = '_' . strtoupper($storedName);

            if ($globalValue = ($GLOBALS[$globalName] ?? null)) {
                $record['php_globals'][$storedName] = $globalValue;
            }
        }

        return $record;
    }
}
