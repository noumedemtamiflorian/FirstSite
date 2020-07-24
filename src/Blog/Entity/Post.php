<?php

namespace App\Blog\Entity;

use DateTime;
use Exception;

class Post
{
    public $id;
    public $name;
    public $slug;
    public $content;
    public $createdAt;
    public $updatedAt;
    public $image;


    public function setCreatedAt($datetime): void
    {
        if (is_string($datetime)) {
            $this->createdAt = new DateTime($datetime);
        }
    }

    /**
     * @param $datetime
     * @throws Exception
     */
    public function setUpdatedAt($datetime): void
    {
        if (is_string($datetime)) {
            $this->updatedAt = new DateTime($datetime);
        }
    }

    public function getThumb()
    {
        ['filename' => $filename, 'extension' => $extension] = pathinfo($this->image);
        return "/uploads/posts/" . $filename . '_thumb.' . $extension;
    }
}
