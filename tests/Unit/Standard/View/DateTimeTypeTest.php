<?php

namespace Psi\Component\ContentType\Tests\Unit\Standard\View;

use Psi\Component\ContentType\Standard\View\DateTimeType;

class DateTimeTypeTest extends TypeTestCase
{
    public function getType()
    {
        return new DateTimeType();
    }

    /**
     * It should provide a formatted view.
     *
     * @dataProvider provideView
     */
    public function testView(array $options, $expected)
    {
        $type = $this->getType();
        $options = $this->resolveOptions($options);
        $view = $this->getType()->createView($this->factory->reveal(), new \DateTime('2016-01-01 00:00:00Z'), $options);
        $this->assertEquals($expected, $view->getFormatted());
    }

    public function provideView()
    {
        return [
            [
                [
                    'date_format' => 'short',
                    'time_format' => 'medium',
                ],
                '01/01/2016, 00:00:00',
            ],
            [
                [
                    'date_format' => 'long',
                    'time_format' => 'medium',
                ],
                '1 January 2016 at 00:00:00',
            ],
            [
                [
                    'date_format' => 'LONG',
                    'time_format' => 'MEDIUM',
                ],
                '1 January 2016 at 00:00:00',
            ],
            [
                [
                    'date_format' => \IntlDateFormatter::LONG,
                    'time_format' => \IntlDateFormatter::MEDIUM,
                ],
                '1 January 2016 at 00:00:00',
            ],
            [
                [
                    'date_format' => 'short',
                    'time_format' => 'none',
                ],
                '01/01/2016',
            ],
        ];
    }

    /**
     * It should throw an exception if an invalid format is given.
     *
     * @expectedException Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     * @expectedExceptionMessage Invalid format "foobar". It must either be one of the \IntlDateFormatter constant values or one of the following strings: "full", "long", "medium", "short", "none"
     */
    public function testInvalidFormat()
    {
        $type = $this->getType();
        $options = $this->resolveOptions([
            'time_format' => 'foobar',
        ]);
        $view = $this->getType()->createView(
            $this->factory->reveal(),
            new \DateTime('2016-01-01 00:00:00Z'),
            $options
        );
        $this->assertEquals($expected, $view->getFormatted());
    }
}
