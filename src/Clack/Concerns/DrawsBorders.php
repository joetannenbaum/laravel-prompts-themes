<?php

namespace App\Clack\Concerns;

trait DrawsBorders
{
    /**
     * Draw a prompt.
     *
     * @return $this
     */
    protected function withBorder(
        string $state,
        string $title,
        string $body,
        string $footer = '',
        string $info = '',
    ): self {
        $symbol = match ($state) {
            'submit' => '◇',
            'error'  => '▲',
            'cancel' => '■',
            default  => '◆',
        };

        $color = match ($state) {
            'submit' => 'gray',
            'error'  => 'yellow',
            'cancel' => 'red',
            default  => 'cyan',
        };

        $symbolColor = match ($state) {
            'submit' => 'cyan',
            default  => $color,
        };

        $bodyLines = collect(explode(PHP_EOL, $body));
        $footerLines = collect(explode(PHP_EOL, $footer))->filter();

        $this->line("{$this->{$symbolColor}($symbol)} {$title}");

        $bodyLines->each(function ($line) use ($color) {
            $this->line("{$this->{$color}('│')} {$line}");
        });

        if ($footerLines->isNotEmpty()) {
            $footerLines->each(function ($line) use ($color) {
                $this->line("{$this->{$color}('│')} {$line}");
            });
        }

        if ($state !== 'submit') {
            $this->line($this->{$color}('└' . ($info ? " {$info} " : '')));
        } else {
            $this->line($this->gray('│'));
        }

        return $this;
    }
}
