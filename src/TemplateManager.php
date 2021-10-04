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
        $APPLICATION_CONTEXT = ApplicationContext::getInstance();

        $quote = (isset($data['quote']) and $data['quote'] instanceof Quote) ? $data['quote'] : null;

        if ($quote)
        {
            $_quoteFromRepository = QuoteRepository::getInstance()->getById($quote->id);
            $usefulObject = SiteRepository::getInstance()->getById($quote->siteId);



            // replace summary html if any
            $text = str_replace(
                '[quote:summary_html]',
                Quote::renderHtml($_quoteFromRepository),
                $text
            );

            // replace summary if any
            $text = str_replace(
                '[quote:summary]',
                Quote::renderText($_quoteFromRepository),
                $text
            );

            $destinationOfQuote = DestinationRepository::getInstance()->getById($quote->destinationId);
            // replace destination_name if any
            $text = str_replace('[quote:destination_name]',$destinationOfQuote->countryName,$text);
        }

        $destinationLinkToReplace = '';
        if(strpos($text, '[quote:destination_link]') !== false){
            $destination = DestinationRepository::getInstance()->getById($quote->destinationId);
            $destinationLinkToReplace = $usefulObject->url . '/' . $destination->countryName . '/quote/' . $_quoteFromRepository->id;
        }

        $text = str_replace('[quote:destination_link]', $destinationLinkToReplace, $text);

        /*
         * USER
         * [user:*]
         */
        $_user  = (isset($data['user'])  and ($data['user']  instanceof User))  ? $data['user']  : $APPLICATION_CONTEXT->getCurrentUser();
        if($_user) {
            $text = str_replace('[user:first_name]', ucfirst(mb_strtolower($_user->firstname)), $text);
        }

        return $text;
    }
}
