<?php

class KnowledgeBaseSearchForm extends Form
{

    /**
     * The ID of Knowledge base Page that the form should redirect to.
     *
     * @var int|null
     */
    protected $kbPageId = null;

    /**
     * Constructor for the Knowledge Base Search Form.
     *
     * @param Controller $controller
     * @param string $name
     * @param int $kbPageId
     */
    public function __construct($controller, $name = 'KnowledgeBaseSearchForm', $kbPageId)
    {
        $fields = FieldList::create(
            AutocompleteField::create(
                'Query',
                'Question',
                FAQ::get()->column('Question')
            )
        );

        $actions = FieldList::create(
            FormAction::create(
                'doSearchKnowledgeBase',
                'Go'
            )
        );

        parent::__construct($controller, $name, $fields, $actions);

        // only a searching form so no need for csrf token
        $this->disableSecurityToken();

        // set faq page id
        if ($kbPageId) {
            $this->kbPageId = $kbPageId;
        }

        // allow custom template to be used based on class name
        $this->setTemplate('KnowledgeBaseSearchForm');

        // allow hook for updating fields and actions if desired
        $this->extend('updateForm', $this);
    }

    /**
     * Handler for the form submission.
     *
     * @param array $data
     * @param Form $form
     *
     * @return SS_HTTPResponse
     */
    public function doSearchKnowledgeBase($data, $form)
    {
        if (!$this->kbPageId) {
            return $this->controller->redirectBack();
        }

        $page = KnowledgebasePage::get()->byID($this->kbPageId);

        // just in case the page doesn't actually exist anymore
        if (!$page) {
            return $this->controller->redirectBack();
        }

        // should either go to the results page or the landing page depending on if there is a query
        $link = (!empty($data['Query']))
            ? sprintf('%s?q=%s', $page->AbsoluteLink(), strip_tags($data['Query']))
            : $page->AbsoluteLink();

        $this->extend('UpdateLink', $link, $data);

        return $this->controller->redirect($link);
    }

}
