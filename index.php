<?php

use App\Clack;
use Laravel\Prompts\ConfirmPrompt;
use Laravel\Prompts\Note;
use Laravel\Prompts\Prompt;
use Laravel\Prompts\SelectPrompt;
use Laravel\Prompts\Spinner;
use Laravel\Prompts\TextPrompt;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\select;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\text;

require __DIR__ . '/vendor/autoload.php';

Prompt::addTheme('clack', [
    TextPrompt::class    => Clack\TextPromptRenderer::class,
    SelectPrompt::class  => Clack\SelectPromptRenderer::class,
    ConfirmPrompt::class => Clack\ConfirmPromptRenderer::class,
    Spinner::class       => Clack\SpinnerRenderer::class,
    Note::class          => Clack\NoteRenderer::class,
]);

Prompt::theme('clack');

intro('Prompts, but make it Clack');

$name = text(
    label: 'What is your name?',
    placeholder: 'Pick a name, any name',
    required: true,
);

$email = text(
    label: 'Email?',
    validate: fn ($val) => str_ends_with($val, '@joe.codes') ? null : 'Must be a @joe.codes email',
);

$confirmationWord = select(
    label: 'Favorite word to confirm an action:',
    options: [
        'Yes',
        'Yup',
        'Yeah',
        'Yezzir',
    ],
);

confirm('Are you sure?', true, $confirmationWord);

spin(fn () => sleep(3), 'Processing...');

outro('Thanks for stopping by!');
