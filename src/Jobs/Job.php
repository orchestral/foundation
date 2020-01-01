<?php

namespace Orchestra\Foundation\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;

abstract class Job
{
    use Dispatchable, Queueable;
}
