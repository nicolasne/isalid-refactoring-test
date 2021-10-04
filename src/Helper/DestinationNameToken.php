<?php

class DestinationNameToken extends TemplateToken {

    protected function getToken()
    {
        return '[quote:destination_name]';
    }

    protected function process(Quote $data, Quote $dataFromRepository, $text)
    {
        $destinationOfQuote = DestinationRepository::getInstance()->getById($data->destinationId);
        return $destinationOfQuote->countryName;
    }
}