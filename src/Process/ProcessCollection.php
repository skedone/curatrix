<?php

namespace Curatrix\Process;

use PhpCollection\Sequence;

class ProcessCollection extends Sequence {

    /**
     * @return int
     */
    public function clear()
    {
        foreach($this->all() as $index => $proc) {
            if(FALSE === $proc->isRunning()) {
                try {
                    $this->remove($index);
                } catch(\Exception $e) {
                    // @todo Catch this fucking error or change the way this collection is managed.
                }
            }
        }

        return $this->count();
    }

    public function resolve($commands = [])
    {
        $active = [];
        foreach($this->all() as $proc) {
            if(empty($active[$proc->getProcessKey()])) {
                $active[$proc->getProcessKey()] = 1;
            } else {
                $active[$proc->getProcessKey()] += 1;
            }
        }

        $should = [];
        foreach($commands as $command) {
            $should[$command['key']] = $command['workers'];
        }

        $do = [];
        foreach($should as $key => $counter) {
            if(empty($active[$key])) {
                $do[$key] = $counter;
            }
            if(!empty($active[$key]) && $active[$key] < $counter ) {
                $do[$key] = ($counter - $active[$key]);
            }
        }

        return $do;
    }
}