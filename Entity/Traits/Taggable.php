<?php

namespace MW\Bundle\TagAdminBundle\Entity\Traits;

use Doctrine\Common\Collections\ArrayCollection;

trait Taggable
{

    protected $tags;
    protected $tags_plain;

    public function getTags()
    {
        $this->tags = $this->tags ? : new ArrayCollection();

        return $this->tags;
    }

    public function getTagsPlain()
    {
        return $this->tags_plain;
    }

    public function setTagsPlain($tags_plain)
    {
        return $this->tags_plain = $tags_plain;
    }

    public function getTaggableType()
    {
        // Strip the namespace and return the lowercase class name
        return strtolower(substr(strrchr(get_class($this), '\\'), 1));
    }

    public function getTaggableId()
    {
        return $this->getId();
    }

}
