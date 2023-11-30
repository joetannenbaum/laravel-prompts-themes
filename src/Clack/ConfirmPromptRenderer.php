<?php

namespace App\Clack;

use Laravel\Prompts\ConfirmPrompt;

class ConfirmPromptRenderer extends Renderer
{
    use Concerns\DrawsBorders;

    /**
     * Render the confirm prompt.
     */
    public function __invoke(ConfirmPrompt $prompt): string
    {
        return match ($prompt->state) {
            'submit' => $this
                ->withBorder(
                    state: $prompt->state,
                    title: $this->truncate($prompt->label, $prompt->terminal()->cols() - 6),
                    body: $this->dim($this->truncate($prompt->label(), $prompt->terminal()->cols() - 6)),
                ),

            'cancel' => $this
                ->withBorder(
                    state: $prompt->state,
                    title: $this->truncate($prompt->label, $prompt->terminal()->cols() - 6),
                    body: $this->renderOptions($prompt),
                    info: 'Cancelled.',
                ),
            'error' => $this
                ->withBorder(
                    state: $prompt->state,
                    title: $this->truncate($prompt->label, $prompt->terminal()->cols() - 6),
                    body: $this->renderOptions($prompt),
                    info: $this->truncate($prompt->error, $prompt->terminal()->cols() - 5),
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
     * Render the confirm prompt options.
     */
    protected function renderOptions(ConfirmPrompt $prompt): string
    {
        $length = (int) floor(($prompt->terminal()->cols() - 14) / 2);
        $yes = $this->truncate($prompt->yes, $length);
        $no = $this->truncate($prompt->no, $length);

        if ($prompt->state === 'cancel') {
            return $this->dim($prompt->confirmed
                ? "● {$yes} / ○ {$no}"
                : "○ {$yes} / ● {$no}");
        }

        return $prompt->confirmed
            ? "{$this->green('●')} {$yes} {$this->dim('/ ○ ' . $no)}"
            : "{$this->dim('○ ' . $yes . ' /')} {$this->green('●')} {$no}";
    }
}
