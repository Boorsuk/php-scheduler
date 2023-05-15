<?php
declare(strict_types=1);


namespace Scheduler\Jobs;

use Cron\CronExpression;
use DateTimeImmutable;
use DateTimeZone;
use Scheduler\Contract\Job;
use Scheduler\Exceptions\InvalidTimezoneException;

abstract class AbstractJob implements Job {

    /**
     * specify which timezone should be used while determining if job is due
     * 
     * @var null|DateTimeZone
     */
    protected ?\DateTimeZone $timezone = null;


    /**
     * specify when the job should run
     * 
     * @var CronExpression
     */
    protected ?CronExpression $cronExpression = null;


    /**
     * job indentifier
     * 
     * @var string
     */
    protected string $id;

    /**
     * setting timezone for job
     * 
     * @throws InvalidTimezoneException
     * @param string|DateTimeZone $timezone 
     * @return self  
     */
    public function setTimezone(string|\DateTimeZone $timezone): self {
        try {
            $tz = is_string($timezone) ? (new \DateTimeZone($timezone)) : $timezone;

        } catch (\Throwable $th) {
            throw new InvalidTimezoneException($th->getMessage());
        }

        $this->timezone = $tz;
        return $this;
    }


    /**
     * return timezone
     * 
     * @return null|DateTimeZone 
     */
    public function getTimezone(): ?\DateTimeZone {
        return $this->timezone;
    }


    /**
     * setting cron expression
     * 
     * @throws \InvalidArgumentException if not a valid CRON expression or any other part 
     * @param string|CronExpression $cronExpression 
     * @return self 
     */
    public function setCronExpression(string|CronExpression $cronExpression): self {
        $this->cronExpression = is_string($cronExpression) ? (new CronExpression($cronExpression)) : $cronExpression;

        return $this;
    }


    /**
     * getCronExpression return (* * * * *) when null
     * 
     * @return CronExpression 
     */
    public function getCronExpression(): CronExpression {
        if(!$this->cronExpression) {
            $this->cronExpression = new CronExpression('* * * * *');
        }

        return $this->cronExpression;
    }


    /**
     * check if job schould be executed
     * 
     * @param DateTimeImmutable $runDateTime 
     * @return bool 
     */
    public function isDue(\DateTimeImmutable $runDateTime): bool {
        
        if($this->timezone) {
            $runDateTime = $runDateTime->setTimezone($this->timezone);
        }

        return $this->getCronExpression()->isDue($runDateTime);
    }


    public function getId(): string {
        return $this->id;
    }
}