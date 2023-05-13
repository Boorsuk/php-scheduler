<?php
declare(strict_types=1);


namespace Scheduler\Jobs;

use Cron\CronExpression;
use DateTimeZone;
use Scheduler\Exceptions\InvalidTimezoneException;


interface Job {

    function run(\DateTimeImmutable $runDateTime): void;

    /**
     * setting timezone which are gona be used while running job
     * 
     * @throws InvalidTimezoneException
     * @param string|DateTimeZone $timezone 
     * @return void 
     */
    function setTimezone(string|\DateTimeZone $timezone): void;

    function getTimezone(): null|\DateTimeZone;

    function setCronExpression(string|CronExpression $cronExpression): void;

    function getCronExpression(): CronExpression;
}