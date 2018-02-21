
# Knowledge-Base

Provides the ability to create a knowledge base. Functionality is based on top of the `silverstripe/faq` module. 

## Features

Uses the [https://github.com/silverstripe/silverstripe-faq](SilverStripe FAQ module) as a base which contains the following features:

- Featured FAQ's
- Categorizing
- Search results will automatically attempt to detect misspellings of search terms.

### Search term synonyms

The use of custom synonym definitions is another way in which misspelling suggestions can be controlled. Synonyms are configured out of the box in this module.

You can configure your synonyms in the Settings tab in the CMS.

*Note that only admin users (who are those with privileges necessary to run the Solr_Configure task) will be able to view and edit this field.*

## Installation

`composer require silverstripe/knowledgebase`

Then ensure to run `$ framework/sake dev/tasks/Solr_Configure` and `$ framework/sake dev/tasks/Solr_Reindex`, and you should have the Knowledge base module ready to use.

*Note:*

*If you are using the `cwp/cwp` module, you will need to ensure you do not enable the `SynonymsSiteConfig` extension in your config as it is enabled in this module by default.*

### Changes to Solr configuration.

The following outlines the changes that have been made to the specified Solr configuration files compared to the default silverstripe/faq settings: 

`knowledgebase/conf/extras/solrconfig.xml`

```
<lst name="spellchecker">
    <str name="name">default</str>
    <str name="field">_text</str>
```

to

```
<lst name="spellchecker">
    <str name="name">default</str>
    <str name="field">_spellcheckText</str>
```

Apply exact figures in `fulltextsearch` module which is needs to work Synonym perfectly. 
[fulltextsearch solrconfig.xml](https://github.com/silverstripe/silverstripe-fulltextsearch/blob/master/conf/solr/4/extras/solrconfig.xml)

````
<float name="accuracy">0</float>
<int name="minQueryLength">1</int>
<float name="maxQueryFrequency">0</float>
```` 

to 
````
<float name="accuracy">0.5</float>
<int name="minQueryLength">4</int>
<float name="maxQueryFrequency">0.01</float>
````

`knowledgebase/conf/extras/schema.ss`

Added the following underneath `$FieldDefinitions`:

````
<field name='_spellcheckText' type='textSpellHtml' indexed='true' stored='false' multiValued='true' />
````

`knowledgebase/conf/extras/types.ss`

Move SynonymFilterFactory to bottom of analyzer to include Synonyms in search results.
[See this pull request for more information](https://github.com/silverstripe/silverstripe-fulltextsearch/pull/156) 
