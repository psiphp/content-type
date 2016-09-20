<?php

namespace Psi\Component\ContentType;

interface ViewInterface
{
    /**
     * Build the view for the field type.
     *
     * This method is passed the builder instance followed by the content view,
     * the data and then the options as defined by the field type.
     *
     * It is the responsiblity of this method to set any data and/or services
     * required to render the data on the frontend website.
     *
     * The builder instance is given for the case where nested views are required
     * (for example in a repeater field, image list, etc).
     */
    public function buildView(ContentViewBuilder $builder, ContentView $view, $data, array $options);
}
