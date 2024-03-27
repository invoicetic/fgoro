<?php

namespace Invoicetic\Fgoro\Tests\Support;

use Invoicetic\Fgoro\Support\CountyFilter;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class CountyFilterTest extends TestCase
{
    /**
     * @param $name
     * @param $result
     * @return void
     * @dataProvider data_filter
     */
    public function test_filter($name, $result)
    {
        $filteredName = CountyFilter::filter($name);
        self::assertSame($result, $filteredName);
    }

    public function data_filter(): array
    {
        return [
            ['Bucureşti', 'Bucuresti'],
            ['Timiş', 'Timis']
        ];
    }
}
