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

        $object->setTagsPlain(implode(', ', $object->getTags()->toArray()));

        return $object;
    }

    public function reverseTransform($object)
    {
        if (!$object) {
            return $object;
        }

        $tags_plain = $object->getTagsPlain();
        $tag_list = array_unique(preg_split('/ *[,\n\r] */', $tags_plain, NULL, PREG_SPLIT_NO_EMPTY));
        $tags = $object->getTags()->toArray();
        foreach ($tag_list as $value) {
            $tag = $this->tagManager->loadOrCreateTag($value);
            if (!in_array($tag, $tags)) {
                $this->tagManager->addTag($tag, $object);
            }
        }

        foreach ($tags as $tag) {
            if (!in_array($tag->__toString(), $tag_list)) {
                $this->tagManager->removeTag($tag, $object);
            }
        }

        return $object;
    }

    // Todo: Move this out into a manager class
    public function postPersist($object)
    {
        $this->tagManager->saveTagging($object);
    }

}