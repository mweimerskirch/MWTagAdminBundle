<?php

namespace MW\Bundle\TagAdminBundle\Admin\Traits;

use Symfony\Component\Form\DataTransformerInterface;

trait TagHandler
{

    protected static $tagTransformer;

    public static function setTagTransformer(DataTransformerInterface $tagTransformer)
    {
        self::$tagTransformer = $tagTransformer;
    }

    public function getFormBuilder()
    {
        $formBuilder = parent::getFormBuilder();
        $formBuilder->addModelTransformer(self::$tagTransformer);
        return $formBuilder;
    }

}
