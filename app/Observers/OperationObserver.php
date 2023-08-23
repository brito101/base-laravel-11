<?php

namespace App\Observers;

use App\Models\Operation;
use App\Models\OperationHistory;
use Illuminate\Support\Facades\Auth;

class OperationObserver
{
    /**
     * Handle the Operation "created" event.
     *
     * @param  \App\Models\Operation  $operation
     * @return void
     */

    public function created(Operation $operation)
    {
        $history = new OperationHistory();
        $history->operation_id = $operation->id;
        $history->action = "criado";
        $history->step_id = $operation->step_id;
        $history->user_id = Auth::user()->id;
        $history->saveQuietly();
    }

    /**
     * Handle the Operation "updated" event.
     *
     * @param  \App\Models\Operation  $operation
     * @return void
     */
    public function updated(Operation $operation)
    {
        $history = new OperationHistory();
        $history->operation_id = $operation->id;
        $history->action = "editado";
        $history->step_id = $operation->step_id;
        $history->user_id = Auth::user()->id;
        $history->saveQuietly();
    }

    /**
     * Handle the Operation "deleted" event.
     *
     * @param  \App\Models\Operation  $operation
     * @return void
     */
    public function deleted(Operation $operation)
    {
        $history = new OperationHistory();
        $history->operation_id = $operation->id;
        $history->action = "deletado";
        $history->step_id = $operation->step_id;
        $history->user_id = Auth::user()->id;
        $history->saveQuietly();
    }

    /**
     * Handle the Operation "restored" event.
     *
     * @param  \App\Models\Operation $operation
     * @return void
     */
    public function restored(Operation $operation)
    {
        $history = new OperationHistory();
        $history->operation_id = $operation->id;
        $history->action = "restaurado";
        $history->step_id = $operation->step_id;
        $history->user_id = Auth::user()->id;
        $history->saveQuietly();
    }

    /**
     * Handle the Operation "force deleted" event.
     *
     * @param  \App\Models\Operation $operation
     * @return void
     */
    public function forceDeleted(Operation $operation)
    {
        $history = new OperationHistory();
        $history->operation_id = $operation->id;
        $history->action = "deletado forçadamente";
        $history->step_id = $operation->step_id;
        $history->user_id = Auth::user()->id;
        $history->saveQuietly();
    }
}
