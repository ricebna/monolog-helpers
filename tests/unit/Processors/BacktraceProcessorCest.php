<?php
/*
 * This file is part of the Monolog Helper package.
 *
 * (c) Phoenix Osiris <phoenix@twistersfury.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwistersFury\Monolog\Tests\Processors;

use TwistersFury\Monolog\Processors\BacktraceProcessor;

class BacktraceProcessorCest
{
    public function testStandardInvoke(\UnitTester $I)
    {
        $testSubject = new BacktraceProcessor();

        $testRecord   = $testSubject([]);
        $backTrace    = debug_backtrace();
        $totalRecords = count($testRecord['extra']['trace']);

        for ($currentPos = 0; $currentPos < $totalRecords; $currentPos++) {
            $I->assertEquals(
                [
                    'type'    => $backTrace[$currentPos + 2]['type'] ?? '',
                    'file'    => $backTrace[$currentPos + 2]['file'] ?? '',
                    'line'    => $backTrace[$currentPos + 2]['line'] ?? '',
                    'message' => $backTrace[$currentPos + 2]['message'] ?? ''
                ],
                $testRecord['extra']['trace'][$currentPos]
            );
        }
    }

    public function testInvokeWithTraceAndSkip(\UnitTester $I)
    {
        $testSubject = new BacktraceProcessor(2);

        $testRecord = $testSubject(
            [
                'trace' => [
                    [],
                    [],
                    [
                        'type'    => 'blah',
                        'file'    => 'something',
                        'line'    => -100,
                        'message' => 'message',
                        'ignored' => 'ignored'
                    ]
                ]
            ]
        );

        $I->assertEquals(
            [
                [
                    'type'    => 'blah',
                    'file'    => 'something',
                    'line'    => -100,
                    'message' => 'message',
                ]
            ],
            $testRecord['extra']['trace']
        );
    }

    public function testInvokeWithSkipEmpty(\UnitTester $I)
    {
        $testSubject = new BacktraceProcessor(2);

        $testRecord = $testSubject(
            [
                'trace' => [
                    [
                        'type'    => '->',
                        'file'    => '',
                        'line'    => '',
                        'message' => '',
                        'ignored' => 'ignored'
                    ]
                ]
            ]
        );

        $I->assertEquals(
            [],
            $testRecord['extra']['trace']
        );
    }
}
