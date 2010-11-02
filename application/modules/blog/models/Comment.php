<?php

/**
 * @Entity
 * @Table(name="comments")
 */
class Blog_Model_Comment
{

    /**
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ManyToOne(targetEntity="Blog_Model_Post", inversedBy="comments")
     * @JoinColumn(name="post_id", nullable=false)
     */
    private $post;
    /** @Column(name="author", type="string", length=40, nullable=false) */
    private $author;
    /** @Column(name="author_email", type="string", length=40, nullable=false) */
    private $author_email;
    /** @Column(name="author_url", type="string", length=40, nullable=true) */
    private $author_url;
    /** @Column(name="author_ip", type="string", length=40, nullable=false) */
    private $author_ip;
    /** @Column(name="content", type="string", length=255, nullable=false) */
    private $content;
    /** @Column(name="parent", type="bigint", nullable=true) */
    private $parent;
    /** @Column(name="approved", type="boolean") */
    private $approved;

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
     * Set author
     *
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * Get author
     *
     * @return string $author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set author_email
     *
     * @param string $authorEmail
     */
    public function setAuthorEmail($authorEmail)
    {
        $this->author_email = $authorEmail;
    }

    /**
     * Get author_email
     *
     * @return string $authorEmail
     */
    public function getAuthorEmail()
    {
        return $this->author_email;
    }

    /**
     * Set author_url
     *
     * @param string $authorUrl
     */
    public function setAuthorUrl($authorUrl)
    {
        $this->author_url = $authorUrl;
    }

    /**
     * Get author_url
     *
     * @return string $authorUrl
     */
    public function getAuthorUrl()
    {
        return $this->author_url;
    }

    /**
     * Set author_ip
     *
     * @param string $authorIp
     */
    public function setAuthorIp($authorIp)
    {
        $this->author_ip = $authorIp;
    }

    /**
     * Get author_ip
     *
     * @return string $authorIp
     */
    public function getAuthorIp()
    {
        return $this->author_ip;
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
     * Set parent
     *
     * @param bigint $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return bigint $parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set approved
     *
     * @param boolean $approved
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;
    }

    /**
     * Get approved
     *
     * @return boolean $approved
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Set post
     *
     * @param Blog_Model_Post $post
     */
    public function setPost(Blog_Model_Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get post
     *
     * @return Blog_Model_Post $post
     */
    public function getPost()
    {
        return $this->post;
    }

}