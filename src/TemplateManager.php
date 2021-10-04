<?php

class TemplateManager
{
    const TOKEN_CLASSES = [
        SummaryHTMLToken::class,
        SummaryToken::class,
        DestinationNameToken::class,
        DestinationLinkToken::class
    ];

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
        foreach (self::TOKEN_CLASSES as $tokenClass){
            $tokenClass = new $tokenClass($quote, $quoteFromRepository);

            $text = $tokenClass->replace($text);
        }

        /*
         * USER
         * [user:*]
         */
        $user = null;
        if(isset($data['user']) &&  $data['user'] instanceof User){
            $user = $data['user'];
        }else{
            // get the current if user is not defined
            $APPLICATION_CONTEXT = ApplicationContext::getInstance();
            $user = $APPLICATION_CONTEXT->getCurrentUser();
        }

        // here $user will be never set to null
        $text = str_replace('[user:first_name]', ucfirst(mb_strtolower($user->firstname)), $text);

        return $text;
    }
}
