<?php

class SummaryHTMLToken extends TemplateToken {

    protected function getToken()
    {
        return '[quote:summary_html]';
    }

    protected function process(Quote $data, Quote $dataFromRepository, $text)
    {
        return Quote::renderHtml($dataFromRepository);
    }
}