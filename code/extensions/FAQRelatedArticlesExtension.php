<?php

class FAQRelatedArticlesExtension extends DataExtension
{

    /**
     * @var array
     */
    private static $many_many = [
        'Tags' => 'TaxonomyTerm',
    ];

    /**
     * @var array
     */
    private static $summary_fields = [
        'TagNameList' => 'Tags',
    ];

    /**
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        // replace default fields
        $fields->replaceField(
            'Tags',
            ListboxField::create(
                'Tags',
                'Tags',
                TaxonomyTerm::get()
                    ->filter('ParentID', FAQ::getRootCategory()->ID)
                    ->map('ID', 'Name')
                    ->toArray()
            )
            ->setMultiple(true)
        );
    }

    /**
     * Helper to get a comma separated list of all the associated tags.
     *
     * @return string
     */
    public function getTagNameList()
    {
        $tags = $this->owner->Tags();
        $names = [];

        foreach ($tags as $tag) {
            $names[] = $tag->Name;
        }

        return implode(', ', $names);
    }

    /**
     * Helper to get all the FAQ articles that are tagged with the same
     * tags.
     *
     * @return DataList|null
     */
    public function getRelatedFAQs($limit = null)
    {
        $tags = $this->owner->Tags();

        if ($tags->count() == 0) {
            return null;
        }

        // find any FAQ that has one of the same tags
        $faqs = FAQ::get()
            ->innerJoin('FAQ_Tags', '"FAQ"."ID" = "FAQ_Tags"."FAQID"')
            ->where(sprintf('"FAQ_Tags"."TaxonomyTermID" IN (%s)', implode(',', $tags->column('ID'))))
            ->exclude('ID', $this->owner->ID);

        // limit the faqs if applicable
        if ($limit && is_int($limit)) {
            $faqs = $faqs->limit($limit);
        }

        return $faqs;
    }

}
