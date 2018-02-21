<?php

/**
 * Provides a field to configure synonyms for Solr in the SiteConfig.
 * This is copied from `SynonymsSiteConfig` in the `cwp/cwp` module.
 *
 * Requires silverstripe/fulltextsearch 1.1.1 or above.
 */
class KnowlegebaseSynonymsSiteConfigExtension extends DataExtension
{

    /**
     * @var array
     */
    private static $db = [
        'SearchSynonyms' => 'Text', // fulltextsearch synonyms.txt content
    ];

    /**
     * @param \FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        $subsiteID = Subsite::currentSubsiteID();

        // Don't show this field if you're not an admin and show only in main site
        // not in subsites
        if (!Permission::check('ADMIN') || $subsiteID != 0) {
            return;
        }

        $searchSynonyms = TextareaField::create('SearchSynonyms', 'Search Synonyms')
            ->setDescription(
                'Enter as many comma separated synonyms as you wish, where ' .
                'each line represents a group of synonyms.<br /> ' .
                'You will need to run <a rel="external" target="_blank" href="dev/tasks/Solr_Configure">Solr_Configure</a> if you make any changes.<br>' .
                'This search synonyms are adding only to KnowledgeBaseIndex, so it will not be added to the other indexes.'
            );

        // Search synonyms
        $fields->addFieldsToTab(
            'Root.FulltextSearch',
            [
                $searchSynonyms,
                LiteralField::create(
                    'notice',
                    '<p class="message notice"><strong>An example of how to add synonyms:</strong><br><br>
                    GB,gib,gigabyte,gigabytes </br>MB,mib,megabyte,megabytes <br>Television, Televisions, TV, TVs<br>
                    <br><br><strong>Synonym mappings can be used for spelling correction too:</strong><br><br>pixima => pixma</p>'
                ),
            ]
        );
    }

    /**
     * @inheritdoc
     *
     * @param ValidationResult $validationResult
     */
    public function validate(ValidationResult $validationResult)
    {
        // Update `cwp/cwp` validator class `SynonymValidator` to `KnowledgebaseSynonymValidator` in our module.
        $validator = new KnowledgebaseSynonymValidator([
            'SearchSynonyms',
        ]);

        $validator->php([
            'SearchSynonyms' => $this->owner->SearchSynonyms,
        ]);

        $errors = $validator->getErrors();

        if (is_array($errors) || $errors instanceof Traversable) {
            foreach ($errors as $error) {
                $validationResult->error($error['message']);
            }
        }
    }
}
