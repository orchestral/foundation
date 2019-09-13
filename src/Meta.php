<?php

namespace Orchestra\Foundation;

use Orchestra\Contracts\Support\DataContainer as DataContainerContract;
use Orchestra\Support\Concerns\DataContainer;

class Meta implements DataContainerContract
{
    use DataContainer;
}
