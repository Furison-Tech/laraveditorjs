<?php

namespace FurisonTech\LaraveditorJS\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeEditorJSFormRequest extends GeneratorCommand
{
    protected $name = 'make:editorjs-request';

    protected $description = 'Create a new EditorJSForm Request class';

    protected $type = 'Request';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/stubs/editorjs-request.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Http\Requests';
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base class import if we are already in base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name): string
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name): self
    {
        $namespace = $this->getNamespace($name);

        $stub = str_replace(
            ['{{ namespace }}', '{{namespace}}'],
            $namespace,
            $stub
        );

        return $this;
    }

}