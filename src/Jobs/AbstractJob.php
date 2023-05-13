<?php
declare(strict_types=1);


namespace Scheduler\Jobs;

use Cron\CronExpression;
use DateTimeZone;
use Scheduler\Exceptions\InvalidTimezoneException;

abstract class AbstractJob implements Job {

    /**
     * specify which timezone should be used while determining if job is due
     * 
     * @var null|DateTimeZone
     */
    protected ?\DateTimeZone $timezone;


    /**
     * specify when the job should run
     * 
     * @var CronExpression
     */
    protected CronExpression $cronExpression;


    /**
     * setting timezone for job
     * 
     * @param string|DateTimeZone $timezone 
     * @return void 
     * @throws InvalidTimezoneException 
     */
    public function setTimezone(string|\DateTimeZone $timezone): void {
        try {
            $tz = is_string($timezone) ? (new \DateTimeZone($timezone)) : $timezone;

        } catch (\Throwable $th) {
            throw new InvalidTimezoneException($th->getMessage());
        }

        $this->timezone = $tz;
    }


    public function getTimezone(): ?\DateTimeZone {
        return $this->timezone;
    }


    /**
     * setting cron expression
     * 
     * @throws \InvalidArgumentException if not a valid CRON expression or any other part 
     * @param string|CronExpression $cronExpression 
     * @return void 
     */
    public function setCronExpression(string|CronExpression $cronExpression): void {
        $this->cronExpression = is_string($cronExpression) ? (new CronExpression($cronExpression)) : $cronExpression;
    }


    public function getCronExpression(): CronExpression {
        if(!$this->cronExpression) {
            $this->cronExpression = new CronExpression('* * * * *');
        }

        return $this->cronExpression;
    }
}