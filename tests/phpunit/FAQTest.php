<?php

class FAQTest extends SapphireTest
{

    /**
     * @var string
     */
    protected static $fixture_file = './FAQTest.yml';

    /**
     * @var array
     */
    protected $requiredExtensions = [
        'FAQ' => [
            'FAQRelatedArticlesExtension',
        ]
    ];

    /**
     * Go through a number of different FAQs that have a variation of attached
     * tags to assert the tag name list.
     */
    public function testGetTagNameList()
    {
        $faq = $this->objFromFixture('FAQ', 'faq1');
        $result = $faq->getTagNameList();

        $this->assertEquals('Category 1', $result);

        $faq = $this->objFromFixture('FAQ', 'faq2');
        $result = $faq->getTagNameList();

        $this->assertEquals('Category 2', $result);

        $faq = $this->objFromFixture('FAQ', 'faq3');
        $result = $faq->getTagNameList();

        $this->assertEquals('Category 1, Category 2', $result);

        $faq = $this->objFromFixture('FAQ', 'faq4');
        $result = $faq->getTagNameList();

        $this->assertEquals('', $result);
    }

    /**
     * Go through a number of different FAQs that have a variation of attached
     * tags to assert the associated faqs.
     */
    public function testGetRelatedFAQs()
    {
        $faq = $this->objFromFixture('FAQ', 'faq1');
        $result = $faq->getRelatedFAQs();

        $this->assertEquals(1, $result->count());
        $this->assertEquals(
            [],
            array_diff(
                $result->column('Question'),
                ['Question 3']
            )
        );

        $faq = $this->objFromFixture('FAQ', 'faq2');
        $result = $faq->getRelatedFAQs();

        $this->assertEquals(1, $result->count());
        $this->assertEquals(
            [],
            array_diff(
                $result->column('Question'),
                ['Question 3']
            )
        );

        $faq = $this->objFromFixture('FAQ', 'faq3');
        $result = $faq->getRelatedFAQs();

        $this->assertEquals(2, $result->count());
        $this->assertEquals(
            [],
            array_diff(
                $result->column('Question'),
                ['Question 1', 'Question 2']
            )
        );

        $faq = $this->objFromFixture('FAQ', 'faq4');
        $result = $faq->getRelatedFAQs();

        $this->assertNull($result);
    }

}
