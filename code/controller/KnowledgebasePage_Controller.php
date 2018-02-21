<?php

/**
 * Extending the existing FAQPage_Controller in order to manipulate Solr
 * Query.
 */
class KnowledgebasePage_Controller extends FAQPage_Controller
{

    /**
     * @var array
     */
    private static $allowed_actions = [
        'view',
    ];

    /**
     * Setting to use our own custom search index.
     *
     * @var string
     */
    public static $search_index_class = 'KnowledgebaseSearchIndex';

    /**
     * Builds a search query from a given search term.
     *
     * Also has an extension hook which allows you to modify the parameters
     * of the query.
     *
     * @param string $keywords
     *
     * @return SearchQuery
     */
    protected function getSearchQuery($keywords)
    {
        $query = parent::getSearchQuery($keywords);

        // add hook to modify search query
        $this->extend('updateSearchQuery', $query);

        return $query;
    }

    /**
     * Overloading view method purely to provide a hook for others to add extra
     * checks or pass additional data to the template.
     *
     * @return array
     */
    public function view()
    {
        $data = parent::view();

        // adding hook to allow checks or additional data to be passed to the template
        $this->extend('onBeforeView', $data);

        return $data;
    }

    /**
     * Overloading view method purely to provide a hook for others to add extra
     * checks or pass additional data to the Search Results.
     *
     * @param array $results
     * @param array $suggestion
     * @param string $keywords
     *
     * @return array
     */
    protected function parseSearchResults($results, $suggestion, $keywords)
    {
        $renderData = parent::parseSearchResults($results, $suggestion, $keywords);

        // adding hook to allow checks or additional data to be passed to the Search Results
        $this->extend('updateParseSearchResults', $renderData);

        return $renderData;
    }
}
