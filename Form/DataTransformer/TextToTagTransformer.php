<?php

namespace MW\Bundle\TagAdminBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineExtensions\Taggable\TagManager;

class TextToTagTransformer implements DataTransformerInterface
{

    /**
     * @var TagManager
     */
    private $tagManager;

    /**
     * @param ObjectManager $om
     */
    public function __construct(TagManager $tagManager)
    {
        $this->tagManager = $tagManager;
    }

    public function transform($object)
    {
        if (null === $object) {
            return $object;
        }

        $this->tagManager->loadTagging($object);

        $object->setTagsPlain(implode(' ', $object->getTags()->toArray()));

        return $object;
    }

    public function reverseTransform($object)
    {
        if (!$object) {
            return $object;
        }

        $tags_plain = $object->getTagsPlain();
        foreach (explode(' ', $tags_plain) as $value) {
            $tag = $this->tagManager->loadOrCreateTag($value);
        }

        $this->tagManager->addTag($tag, $object);

        // FIXME: Currently, tagging is saved *before* the element, even if it doesn't validate
        $this->tagManager->saveTagging($object);

        return $object;
    }

}