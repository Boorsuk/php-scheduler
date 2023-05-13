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
     * @param string|\DateTimeZone $timezone 
     * @return void 
     */
    function setTimezone(string|\DateTimeZone $timezone): self;


    /**
     * return timezone
     * 
     * @return null|\DateTimeZone 
     */
    function getTimezone(): ?\DateTimeZone;


    /**
     * setting cron expression
     * 
     * @throws \InvalidArgumentException if not a valid CRON expression or any other part 
     * @param string|CronExpression $cronExpression 
     * @return void 
     */
    function setCronExpression(string|CronExpression $cronExpression): self;


    /**
     * return CronExpression(* * * * *) when $cronExpression is null
     * 
     * @return CronExpression 
     */
    function getCronExpression(): CronExpression;


    /**
     * check if job schould be executed
     * 
     * @param \DateTimeImmutable $runDateTime 
     * @return bool 
     */
    function isDue(\DateTimeImmutable $runDateTime): bool;
}