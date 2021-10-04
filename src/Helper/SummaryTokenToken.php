<?php

class SummaryToken extends TemplateToken {

    protected function getToken()
    {
        return '[quote:summary]';
    }

    protected function process(Quote $data, Quote $dataFromRepository, $text)
    {
        return Quote::renderText($dataFromRepository);
    }
}