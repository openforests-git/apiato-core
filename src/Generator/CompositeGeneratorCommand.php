<?php

namespace Apiato\Generator;

abstract class CompositeGeneratorCommand extends Generator
{
    public function handle(): void
    {
        parent::handle();

        $this->askSection();

        $this->askContainer();

        $this->askCustomInputs();

        $this->runGeneratorCommands();
    }
}
