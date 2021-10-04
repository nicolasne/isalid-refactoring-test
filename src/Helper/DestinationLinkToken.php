<?php

class DestinationLinkToken extends TemplateToken {

    protected function getToken()
    {
        return '[quote:destination_link]';
    }

    protected function process(Quote $data, Quote $dataFromRepository, $text)
    {
        $usefulObject = SiteRepository::getInstance()->getById($dataFromRepository->siteId);
        $destination = DestinationRepository::getInstance()->getById($dataFromRepository->destinationId);
        $destinationLinkToReplace = $usefulObject->url . '/' . $destination->countryName . '/quote/' . $dataFromRepository->id;

        return $destinationLinkToReplace;
    }
}