<?php
declare(strict_types=1);


namespace Test\Jobs;

use DateTimeImmutable;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Scheduler\Exceptions\InvalidTimezoneException;
use Scheduler\Jobs\AbstractJob;
use Scheduler\Jobs\Job;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertSame;

class AbstractJobTest extends TestCase {

    private Job $testedClass;

    protected function setUp(): void {
        $this->testedClass = new class extends AbstractJob {
            public function run(DateTimeImmutable $runDateTime): void { }
        };
    }

    /**
     * @test
     * @return void 
     */
    public function SHOULD_THROW_InvalidTimezoneException_WHEN_PASSED_INVALID_TIMEZONE() {
        // given
        $testedClass    = $this->testedClass;
        $timezone       = 'hello';
        $error          = null;

        //when
        try {
            $testedClass->setTimezone($timezone);
        } catch (\Throwable $th) {
            $error = $th;
        }
        
        assertNotNull($error);
        assertInstanceOf(InvalidTimezoneException::class, $error);
    }


    /**
     * @test
     * @return void  
     */
    public function SHOULD_RETURN_DEFAULT_CRON_EXPRESSION_WHEN_REQUESTED_PROPERTY_IS_NULL() { 
        // given
        $testedClass        = $this->testedClass;
        $expectedExpression = '* * * * *';
        $actualExpression   = null;

        // when
        $actualExpression = $testedClass->getCronExpression();

        // then
        assertNotNull($actualExpression);
        assertEquals($expectedExpression, $actualExpression->getExpression());
    }

    /**
     * @test
     * @dataProvider isDueDataProvider
     * @return void 
     */
    public function IS_DUE_SHOULD_RETURN_EXPECTED_VALUE_BASE_ON_TIMEZONE(
        string $timezone,
        bool $expectedResult
    ) {
        //given
        $runDateTime    = new \DateTimeImmutable('2023-03-11 11:30:00', new \DateTimeZone('Europe/Warsaw')); // @notice this is CET time (UTC + 1)
        $cronExpression = new \Cron\CronExpression('* 11,8,3,5,1,17,19 11 * *');
        $testedClass    = $this->testedClass;
       
        $testedClass->setCronExpression($cronExpression);
        $testedClass->setTimezone($timezone);
        
        // when
        $result = $testedClass->isDue($runDateTime);

        //then
        assertSame($expectedResult, $result, $timezone);
    }

    public static function isDueDataProvider() {
        return [
            ['America/Los_Angeles', false],
            ['America/New_York', true],
            ['America/Sao_Paulo', false],
            ['Asia/Tokyo', true],
            ['Europe/London', false],
            ['Asia/Singapore', false],
        ];
    }
}