<?php


namespace Tests\Framework\Twig;


use App\Framework\Twig\FormExtension;
use App\Framework\Twig\TimeExtension;
use DateTime;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;

class TimeExtensionTest extends TestCase
{

    /**
     * @var TimeExtension
     */
    private TimeExtension $timeExtension;

    public function setUp(): void
    {
        $this->timeExtension = new TimeExtension();
    }

    public function testDateFormat()
    {
        $format = 'd-m-Y H:i';
        $date = new DateTime();
        $result = '<time class="timeago" datetime="' . $date->format(DateTime::ATOM) . '"> ' . $date->format($format) . '</time>';
        $this->assertEquals($result, $this->timeExtension->ago($date));
    }

}
