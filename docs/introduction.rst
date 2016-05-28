CMF Content Type Component
==========================

.. note::

    This component does not exist, this is just an idea.

What is it?
-----------

The ContentType component provides a way to group together fields in a
semantic way, making available a form for the administration of the data and
rendering a view for the presentation of the data on the frontend website.

Getting Started
---------------

Lets start by creating a content type which will show a list of news items. It
will be made up of a ``title`` and a ``list`` of news items.

When you define a new content type you will need to create an object to which
it will be mapped, create a really simple ``NewsList`` object:

.. code-block:: php

    <?php

    class Newlist
    {
        public $title;
        public $list;
    }

Now we can define a content type for it:

.. code-block:: yaml

    cmf_content_type:
        types:
            news_list:
                fields:
                    title:
                        type: string
                        required: true
                    list:
                        type: children_collection
                        defaults:
                            parent_path: /news
                            limit: 5
                object: AppBundle\Entity\NewsList
                driver: doctrine/orm

Above we defined ``$title`` as a simple scalar ``string``, but ``$list`` is of 
the type ``children_collection`` - this is a composite type which contains
multiple scalar fields of its own (it is infact another content type defined
elsewhere, we will discuss this later).

We then declared that we are mapping to an object of class
``AppBundle\Entity\NewsList`` and that we will be using the Doctrine ORM as a
storage layer.

Form Rendering
~~~~~~~~~~~~~~

Now you want to add a form to your backoffice, this as simple asking the
``content_type.form.registry`` service for your content type:

.. code-block:: php

    <?php

    // this is the data from the request
    $data = [
        'title' => 'Hello World!',
        'list' => [
            'parent_path' => '/cms/news',
            'limit' => 5,
        ],
    ];

    // get the form
    $form = $contentTypeRegistry->get('news_list');

    // submit the data (bypassing validation etc..)
    $form->submit($data);
    $newsList = $form->getData();

    // persist the object
    $entityManager->persist($newsList);
    $entityManager->flush();

And it is as simple as that.

Frontend (website) Rendering
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Later, your decide that you want to render this news list on your website,
this can be achieved with a call to the ``cmf_content_type.view_builder``:

.. code-block:: php

    <?php

    $newsList = $entityManager->find('NewsList', $_GET['id']);
    $view = $viewBuilder->build('news_list', $newsList);

    echo $view['title']; // Hello World!
    echo $view['list']['collection']->count(); // number of news items
    echo $view['list']['limit']; // 5
    echo $view['list']['paginator']; // a paginator instance

So far so good, you now have a ``ContentView`` object with all the data that
you need to render your content type, but what if you are lazy and do not want
to write any HTML today?

Like the Symfony form component, it is possible to render a HTML view of the
content type:

.. code-block:: jinja

    <h1>My Website</h1>
    {{ cmf_content_type_render(view) }}

Will render something like:

.. code-block:: html

    <h1>My Website</h1>
    <div class="news_list">
        <h3>Hello World</h3>
        <div class="list">
            <div class="element">
                <h4><a href="/path/to/article">News 1</a></h4>
                <p>This is news one</p>
            </div>
            <div class="element">
                <h4><a href="/path/to/article">News 2</a></h4>
                <p>This is news two</p>
            </div>
            <div class="element">
                <h4><a href="/path/to/article">News 3</a></h4>
                <p>This is news three</p>
            </div>
        </div>
    </div>

Custom Template
~~~~~~~~~~~~~~~

Which is probably completely inappropriate for your website, so lets alter our
original content type configuration to use a custom template:

.. code-block:: yaml

    cmf_content_type:
        types:
            news_list:
                template: AppBundle:ContentType:NewsList.html.twig
                object: AppBundle\Entity\NewsList
                fields:
                    title:
                        type: string
                        required: true
                    list:
                        type: children_collection
                        defaults:
                            parent_path: /news
                            limit: 5

Storage
-------

You will make an informed choice about which storage layer you choose. Both
Doctrine ORM and Doctrine PHPCR-ODM are supported by default.

How are composite types stored? Every composite type (f.e. the
``children_collection`` above) has its own Value object (i.e. a
plain-old-PHP-object). So after submitting a form we have a data structure
such as:

.. code-block::

    NewsList {
        title => Hello World
        list => ChildrenCollectionType {
            parent_path => /cms/news
            limit => 5
        }
    }

When you choose a driver the ContentType library will automatically generate
the mapping for your chosen object (i.e. ``AppBundle\Entity\NewsList``). This
is good because it means that you do not have to do anything beyond defining
your ``NewsList`` class.

Whats going on behind the scenes? I the case of the Doctrine ORM it will map
the ``ChildrenCollectionType`` as an *embeddable object* on ``NewsList`` -
this means that all the data is stored in a single table and it is *fast*.

In the case of the (hierarhical) Doctrine PHPCR-ODM it will map the
``ChildrenCollectionType`` as a child normal object.
