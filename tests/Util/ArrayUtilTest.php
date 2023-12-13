<?php

namespace Faibl\MailjetBundle\Tests\Util;

use Faibl\MailjetBundle\Util\ArrayUtil;
use PHPUnit\Framework\TestCase;

class ArrayUtilTest extends TestCase
{
    /**
     * @covers ArrayUtil
     * @covers filter empty
     */
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

    /**
     * @covers ArrayUtil
     * @covers filter empty recursive
     */
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

    /**
     * @covers ArrayUtil
     * @covers string to array
     */
    public function test_string_to_array()
    {
        $this->assertEquals([], ArrayUtil::stringToArray(null), 'Test null converted to empty array');
        $this->assertEquals([], ArrayUtil::stringToArray(''), 'Test empty string converted to empty array');
        $this->assertEquals(['item1'], ArrayUtil::stringToArray('item1'), 'Test single value converted to array');
        $this->assertEquals(['item1','item2'], ArrayUtil::stringToArray('item1,item2'), 'Test multiple values converted to array');
        $this->assertEquals(['item1','item2','item3'], ArrayUtil::stringToArray('item1; item2  ;   item3', ';'), 'Test trim and separator');
    }
}
