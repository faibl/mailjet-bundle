<?php

namespace Faibl\MailjetBundle\Tests\Util;

use Faibl\MailjetBundle\Util\ArrayUtil;
use PHPUnit\Framework\TestCase;

class ArrayUtilTest extends TestCase
{
    public function test_filter_empty()
    {
        $array = [
            0 => 0,
            1 => null,
            2 => 'string',
            4 => null,
            10 => 10,
            null,
            'string2',
            null,
        ];

        $expected = [
            2 => 'string',
            10 => 10,
            12 => 'string2',
        ];

        $this->assertEquals($expected, ArrayUtil::filterEmpty($array), 'Test filter empty values from array');
    }

    public function test_filter_empty_recursive()
    {
        $array = [
            0 => 0,
            1 => null,
            2 => 'string',
            4 => null,
            10 => 10,
            null,
            'string2',
            null,
            [
                0 => 0,
                1 => null,
                2 => 'string',
                4 => null,
                10 => 10,
                null,
                'string2',
                null,
            ]
        ];

        $expected = [
            2 => 'string',
            10 => 10,
            12 => 'string2',
            14 => [
                2 => 'string',
                10 => 10,
                12 => 'string2',
            ]
        ];

        $this->assertEquals($expected, ArrayUtil::filterEmptyRecursive($array), 'Test filter empty values from nested array');
    }
}
