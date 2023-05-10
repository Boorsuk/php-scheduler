<?php
declare(strict_types=1);


namespace Scheduler\Jobs;


interface Job {
    function run(): void;
}