<?php

/**
 * @Entity
 * @Table(name="posts")
 */
class Blog_Model_Post
{

    /**
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @OneToMany(targetEntity="Blog_Model_Comment", mappedBy="post", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
     */
    private $comments;
    /**
     * @Column(name="title", type="string", length="127")
     */
    private $title;
    /**
     * @Column(name="slug", type="string", length="127")
     */
    private $slug;
    /** @Column(name="content", type="text") */
    private $content;
    /** @Column(type="datetime") */
    private $created;
    /** @Column(type="datetime") */
    private $updated;


    public function __construct()
    {
        // constructor is never called by Doctrine
        $this->created = $this->updated = new DateTime("now");
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @PreUpdate
     */
    public function updated()
    {
        $this->updated = new DateTime("now");
    }

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        if ($this->slug == null) {
            $this->slug = $this->_slugize($title);
        }
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Put this method in if your slug should be "editable"
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Set content
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return string $content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set created
     *
     * @param datetime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * Get created
     *
     * @return datetime $created
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param datetime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * Get updated
     *
     * @return datetime $updated
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Add comments
     *
     * @param Blog_Model_Comment $comments
     */
    public function addComments(Blog_Model_Comment $comments)
    {
        $this->comments[] = $comments;
    }

    /**
     * Get comments
     *
     * @return Doctrine\Common\Collections\Collection $comments
     */
    public function getComments()
    {
        return $this->comments;
    }

    public function toString()
    {
        return $this->title;
    }

    /**
     * utility function for generating slug
     *
     * @param string $str
     * @param string $separator
     * @param string $lowercase
     * @return string
     */
    private function _slugize($str, $separator = 'dash', $lowercase = FALSE)
    {
        if ($separator == 'dash') {
            $search = '_';
            $replace = '-';
        } else {
            $search = '-';
            $replace = '_';
        }

        $trans = array(
            '&\#\d+?;' => '',
            '&\S+?;' => '',
            '\s+' => $replace,
            '[^a-z0-9\-\._]' => '',
            $replace . '+' => $replace,
            $replace . '$' => $replace,
            '^' . $replace => $replace,
            '\.+$' => ''
        );

        $str = strip_tags($str);

        foreach ($trans as $key => $val) {
            $str = preg_replace("#" . $key . "#i", $val, $str);
        }

        if ($lowercase === TRUE) {
            $str = strtolower($str);
        }

        return trim(stripslashes($str));
    }

}