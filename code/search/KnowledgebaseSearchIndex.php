<?php

/**
 * Custom Solr search index. Extends {@see FAQSearchIndex}
 * and adds customization capabilities to change Solr configuration (.solr folder) only for this index.
 */
class KnowledgebaseSearchIndex extends FAQSearchIndex
{

    /**
     * We are calling the init method from the FAQSearchIndex in order to
     * create a hook extension to make it modifiable. This will allow for
     * the addition of custom fields to the FAQSearchIndex.
     */
    public function init()
    {
        parent::init();

        $this->extend('onAfterInit', $this);
    }

    /**
     * Upload config for this index to the given store
     *
     * @param SolrConfigStore $store
     */
    public function uploadConfig($store)
    {
        parent::uploadConfig($store);

        // Upload configured synonyms {@see SynonymsKnowlegebaseSiteConfig}
        // Getting the Main site Configuration. because Synonyms text field only
        // enabled in main site( subsite 0)
        $siteConfig = SiteConfig::get()->filter([
            "SubsiteID" => 0,
        ])->First();

        if ($siteConfig->SearchSynonyms) {
            $store->uploadString(
                $this->getIndexName(),
                'synonyms.txt',
                $siteConfig->SearchSynonyms
            );
        }
    }
}

