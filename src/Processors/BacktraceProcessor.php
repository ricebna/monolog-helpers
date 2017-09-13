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
 * Allows you to easily add backtraces to logging.
 *
 * @package TwistersFury\Monolog\Processors
 */
class BacktraceProcessor
{
    /**
     * @var int The initial traces to skip when processing.
     */
    protected $skipLevel = 3;

    protected $skipEmpty = true;

    public function __construct(int $skipLevel = null, bool $skipEmpty = null)
    {
        if ($skipLevel !== null) {
            $this->skipLevel = $skipLevel;
        }

        if ($skipEmpty !== null) {
            $this->skipEmpty = $skipEmpty;
        }
    }

    public function __invoke(array $record): array
    {
        //Pull Backtrace from either debug_backtrace or Record data.
        $backTrace = debug_backtrace();
        if (isset($record['trace'])) {
            $backTrace = $record['trace'];
        } elseif (isset($record['extra']['trace'])) {
            $backTrace = $record['extra']['trace'];
        }

        $record['extra']['trace'] = [];

        $currentPos = 0;

        $defaultTrace = [
            'type'    => '->',
            'file'    => '',
            'line'    => '',
            'message' => ''
        ];

        foreach ($backTrace as $traceItem) {
            //Skipping Initial Traces
            if ($currentPos++ < $this->skipLevel) {
                continue;
            }

            //Merging/Intersecting Traces
            $traceItem = array_merge(
                $defaultTrace,
                array_intersect_key(
                    $traceItem,
                    $defaultTrace
                )
            );

            if (!$this->skipEmpty || $traceItem != $defaultTrace) {
                $record['extra']['trace'][] = $traceItem;
            }
        }

        return $record;
    }
}
