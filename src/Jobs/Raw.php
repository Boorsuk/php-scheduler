<?php
declare(strict_types=1);


namespace Scheduler\Jobs;

use DateTimeImmutable;
use Laravel\SerializableClosure\SerializableClosure;

class Raw extends AbstractJob {

    /**
     *  
     * @var callable
     */
    private $callback;


    private array $args = [];


    public function __construct(
        callable $callback,
        array $args = [],
        ?string $id = null 
    ) {
        $this->callback = $callback;
        $this->args     = $args;

        $this->id = $id ?? $this->generateId($callback);
    }


    public function run(DateTimeImmutable $runDateTime): void {
        
        if(!$this->isDue($runDateTime)) {
            return;
        }

        call_user_func_array($this->callback, $this->args);
    }


    private function generateId(callable $callback):string {
        return $callback instanceof \Closure ? 
            md5(serialize(new SerializableClosure($callback))) :
            md5(serialize($callback));
    }
}