<?php
declare(strict_types=1);


namespace Scheduler\Jobs;

use DateTimeImmutable;
use Scheduler\Jobs\AbstractJob;

class PhpJob extends AbstractJob {

    public function run(DateTimeImmutable $runDateTime): void {
        
        if(!$this->isDue($runDateTime)) {
            return;
        }
        
        echo 'Hello World ;)';
    }

}