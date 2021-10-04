<?php

abstract class TemplateToken {
    private $data;
    private $dataFromRepository;

    public function __construct($data, $dataFromRepository)
    {
        $this->data = $data;
        $this->dataFromRepository = $dataFromRepository;
    }

    protected abstract function getToken();

    protected abstract function process(Quote $data, Quote $dataFromRepository, $text);

    public function replace($text)
    {
        return str_replace(
            $this->getToken(),
            $this->process($this->data, $this->dataFromRepository, $text),
            $text
        );
    }
}