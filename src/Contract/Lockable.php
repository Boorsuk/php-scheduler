<?php
declare(strict_types=1);


namespace Scheduler\Contract;


interface Lockable {

    /**
     * Checks if the object is locked.
     * 
     * @return bool 
     */
    function isLocked():bool;
  

    /**
     * acquire lock
     *
     * @return true if the object was locked successfully.
     */
    function acquire();
  

    /**
     * release lock
     * 
     * @return mixed 
     */
    function release();

}