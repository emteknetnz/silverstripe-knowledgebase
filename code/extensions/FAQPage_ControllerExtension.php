<?php

class FAQPage_ControllerExtension extends DataExtension
{
    /**
     * We are using the `KnowledgebaseSearchIndex` Solr index instead of the `FAQSearchIndex`
     * in our knowledge base module so we can add custom Solr configuration.
     *
     * We are using this hook to update FAQPage_Controller static before the index method is called
     * so that it uses the our custom index.
     */
    public function onBeforeInit()
    {
        FAQPage_Controller::$search_index_class = 'KnowledgebaseSearchIndex';
    }
}
