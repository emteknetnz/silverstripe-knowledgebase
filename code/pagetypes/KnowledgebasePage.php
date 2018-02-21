<?php

/**
 * To create knowledge base pages instead of FAQ pages.
 *
 * Class KnowledgeBasePage
 */
class KnowledgebasePage extends FAQPage
{
    /**
     * @var array
     */
    private static $db = [
        'NoResultsMessage' => 'HTMLText',
    ];

    /**
     * @var string
     */
    static $hide_ancestor = 'FAQPage';

    /**
     * @var string
     */
    private static $singular_name = 'Knowledge base Page';

    /**
     * @var string
     */
    private static $description = 'Knowledge base search page';

    /**
     * @return \FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->replaceField(
            'NoResultsMessage',
            HTMLEditorField::create(
                'NoResultsMessage',
                'No Results Message'
            )
        );

        return $fields;
    }

}
