<?php

namespace App\Clack;

use Laravel\Prompts\Note;

class NoteRenderer extends Renderer
{
    /**
     * Render the note.
     */
    public function __invoke(Note $note): string
    {
        $lines = collect(explode(PHP_EOL, $note->message));

        switch ($note->type) {
            case 'intro':
            case 'outro':
                $prefix = $note->type === 'intro' ? '┌' : '└';

                if ($note->type === 'intro') {
                    $this->newLine();
                }

                $lines->each(function ($line, $index) use ($prefix) {
                    $this->line($this->gray(($index === 0 ? $prefix : '│') . ' ') . $this->bgCyan($this->black(' ' . $line . ' ')));
                });

                if ($note->type === 'intro') {
                    $this->line($this->gray('│'));
                } else {
                    $this->newLine();
                }

                return $this;

            case 'warning':
                $lines->each(fn ($line) => $this->line($this->yellow(" {$line}")));

                return $this;

            case 'error':
                $lines->each(fn ($line) => $this->line($this->red(" {$line}")));

                return $this;

            case 'alert':
                $lines->each(fn ($line) => $this->line(" {$this->bgRed($this->white(" {$line} "))}"));

                return $this;

            case 'info':
                $lines->each(fn ($line) => $this->line($this->green(" {$line}")));

                return $this;

            default:
                $lines->each(fn ($line) => $this->line(" {$line}"));

                return $this;
        }
    }
}
