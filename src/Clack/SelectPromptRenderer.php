<?php

namespace App\Clack;

use App\Clack\Concerns\DrawsBorders;
use Laravel\Prompts\SelectPrompt;
use Laravel\Prompts\Themes\Contracts\Scrolling;
use Laravel\Prompts\Themes\Default\Concerns\DrawsBoxes;
use Laravel\Prompts\Themes\Default\Concerns\DrawsScrollbars;

class SelectPromptRenderer extends Renderer implements Scrolling
{
    use DrawsBorders;
    use DrawsBoxes;
    use DrawsScrollbars;

    /**
     * Render the select prompt.
     */
    public function __invoke(SelectPrompt $prompt): string
    {
        $maxWidth = $prompt->terminal()->cols() - 6;

        return match ($prompt->state) {
            'submit' => $this
                ->withBorder(
                    state: $prompt->state,
                    title: $this->truncate($prompt->label, $prompt->terminal()->cols() - 6),
                    body: $this->dim($this->truncate($prompt->label(), $maxWidth)),
                ),
            'cancel' => $this
                ->withBorder(
                    state: $prompt->state,
                    title: $this->truncate($prompt->label, $prompt->terminal()->cols() - 6),
                    body: $this->renderOptions($prompt),
                    info: 'Cancelled.'
                ),
            'error' => $this
                ->withBorder(
                    state: $prompt->state,
                    title: $this->truncate($prompt->label, $prompt->terminal()->cols() - 6),
                    body: $this->renderOptions($prompt),
                    info: $this->truncate($prompt->error, $prompt->terminal()->cols() - 5)
                ),

            default => $this
                ->withBorder(
                    state: $prompt->state,
                    title: $this->truncate($prompt->label, $prompt->terminal()->cols() - 6),
                    body: $this->renderOptions($prompt),
                    info: $prompt->hint,
                ),
        };
    }

    /**
     * The number of lines to reserve outside of the scrollable area.
     */
    public function reservedLines(): int
    {
        return 5;
    }

    /**
     * Render the options.
     */
    protected function renderOptions(SelectPrompt $prompt): string
    {
        return $this->scrollbar(
            collect($prompt->visible())
                ->map(fn ($label) => $this->truncate($label, $prompt->terminal()->cols() - 12))
                ->map(function ($label, $key) use ($prompt) {
                    $index = array_search($key, array_keys($prompt->options));

                    if ($prompt->state === 'cancel') {
                        return $this->dim(
                            $prompt->highlighted === $index
                                ? " {$this->green('●')} {$label}"
                                : " ○ {$label}"
                        );
                    }

                    return $prompt->highlighted === $index
                        ? " {$this->green('●')} {$label}"
                        : " {$this->dim('○')} {$this->dim($label)}";
                })
                ->values(),
            $prompt->firstVisible,
            $prompt->scroll,
            count($prompt->options),
            min($this->longest($prompt->options, padding: 6), $prompt->terminal()->cols() - 6),
            $prompt->state === 'cancel' ? 'dim' : 'cyan'
        )->implode(PHP_EOL);
    }
}
