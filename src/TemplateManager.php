<?php

class TemplateManager
{
    public function getTemplateComputed(Template $tpl, array $data)
    {
        if (!$tpl) {
            throw new \RuntimeException('no tpl given');
        }

        $replaced = clone($tpl);
        $replaced->subject = $this->computeText($replaced->subject, $data);
        $replaced->content = $this->computeText($replaced->content, $data);

        return $replaced;
    }

    private function computeText($text, array $data)
    {
        if(!isset($data['quote'])){
            return $text;
        }

        if(!$data['quote'] instanceof Quote){
            return $text;
        }

        $quote = $data['quote'];

        $quoteFromRepository = QuoteRepository::getInstance()->getById($quote->id);

        // replace summary html if any
        $text = str_replace(
            '[quote:summary_html]',
            Quote::renderHtml($quoteFromRepository),
            $text
        );

        // replace summary if any
        $text = str_replace(
            '[quote:summary]',
            Quote::renderText($quoteFromRepository),
            $text
        );

        $destinationOfQuote = DestinationRepository::getInstance()->getById($quote->destinationId);
        // replace destination_name if any
        $text = str_replace('[quote:destination_name]',$destinationOfQuote->countryName,$text);


        $destinationLinkToReplace = '';
        if(strpos($text, '[quote:destination_link]') !== false){
            $usefulObject = SiteRepository::getInstance()->getById($quote->siteId);
            $destination = DestinationRepository::getInstance()->getById($quote->destinationId);
            $destinationLinkToReplace = $usefulObject->url . '/' . $destination->countryName . '/quote/' . $quoteFromRepository->id;
        }

        $text = str_replace('[quote:destination_link]', $destinationLinkToReplace, $text);

        /*
         * USER
         * [user:*]
         */
        $user = null;
        if(isset($data['user']) &&  $data['user']  instanceof User){
            $user = $data['user'];
        }else{
            // get the current if user is not defined
            $APPLICATION_CONTEXT = ApplicationContext::getInstance();
            $user = $APPLICATION_CONTEXT->getCurrentUser();
        }

        // here $user wiil be never set to null
        $text = str_replace('[user:first_name]', ucfirst(mb_strtolower($user->firstname)), $text);

        return $text;
    }
}
