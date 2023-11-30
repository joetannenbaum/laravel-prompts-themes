<?php

namespace App\Clack;

use App\Clack\Concerns\DrawsBorders;
use Laravel\Prompts\TextPrompt;

class TextPromptRenderer extends Renderer
{
    use DrawsBorders;

    /**
     * Render the text prompt.
     */
    public function __invoke(TextPrompt $prompt): string
    {
        $maxWidth = $prompt->terminal()->cols() - 6;

        return match ($prompt->state) {
            'submit' => $this
                ->withBorder(
                    state: $prompt->state,
                    title: $this->truncate($prompt->label, $prompt->terminal()->cols() - 6),
                    body: $this->dim($this->truncate($prompt->value(), $maxWidth)),
                ),

            'cancel' => $this
                ->withBorder(
                    state: $prompt->state,
                    title: $this->truncate($prompt->label, $prompt->terminal()->cols() - 6),
                    body: $this->dim($this->truncate($prompt->value(), $maxWidth)),
                    info: 'Cancelled.',
                ),
            // ->box(
            //     $this->truncate($prompt->label, $prompt->terminal()->cols() - 6),
            //     $this->strikethrough($this->dim($this->truncate($prompt->value() ?: $prompt->placeholder, $maxWidth))),
            //     color: 'red',
            // )
            // ->error('Cancelled.'),

            'error' => $this
                ->withBorder(
                    state: $prompt->state,
                    title: $this->truncate($prompt->label, $prompt->terminal()->cols() - 6),
                    body: $prompt->valueWithCursor($maxWidth),
                    info: $this->truncate($prompt->error, $prompt->terminal()->cols() - 5),
                ),

            default => $this->withBorder(
                state: $prompt->state,
                title: $this->truncate($prompt->label, $prompt->terminal()->cols() - 6),
                body: $prompt->valueWithCursor($maxWidth),
                info: $prompt->hint,
            )
        };
    }
}
