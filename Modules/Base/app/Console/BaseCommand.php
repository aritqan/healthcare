<?php

namespace Modules\Base\Console;

use Illuminate\Console\Command;

class BaseCommand extends Command
{
   public $data = [];

    public function __construct()
    {
        parent::__construct();
    }

    protected function getValidInput(string $question, string $error = 'The input is invalid.')
    {
        $input = $this->ask($question);

        if(empty($input)) {
            $this->error($error);
            return $this->getValidInput($question, $error);
        }

        return $input;
    }
}
