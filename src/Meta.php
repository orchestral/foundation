<?php

namespace Orchestra\Foundation;

use Orchestra\Support\Concerns\DataContainer;
use Orchestra\Contracts\Support\DataContainer as DataContainerContract;

class Meta implements DataContainerContract
{
    use DataContainer;
}
