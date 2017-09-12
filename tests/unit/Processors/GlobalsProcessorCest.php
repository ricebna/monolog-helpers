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

use TwistersFury\Monolog\Processors\GlobalsProcessor;

class GlobalsProcessorCest
{
    public function testStandardInvoke(\UnitTester $I)
    {
        $_POST['test'] = 'value';

        $testSubject = new GlobalsProcessor(['post' => true]);
        $testRecord  = $testSubject([]);

        $I->assertEquals(
            [
                'extra' => [
                    'php_globals' => [
                        'post' => [
                            'test' => 'value',
                        ]
                    ]
                ]
            ],
            $testRecord
        );
    }
}
