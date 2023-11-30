<?php

namespace App\Clack;

use Laravel\Prompts\Themes\Default\Renderer as DefaultRenderer;

abstract class Renderer extends DefaultRenderer
{
    /**
     * Render the output with a blank line above and below.
     */
    public function __toString()
    {
        return $this->output;
    }
}
