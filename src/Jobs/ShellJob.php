<?php 
declare(strict_types=1);


namespace Scheduler\Jobs;


class ShellJob extends AbstractJob {

    public function run(): void {
        echo 'x';
    }
    
}