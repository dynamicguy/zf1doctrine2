<?php

class Blog_Model_Dao_Post
{

    /**
     * @var instance of Doctrine\ORM\EntityManager
     */
    protected $_em;

    public function __construct()
    {
        $this->_em = Blog_Api_Util_Bootstrap::getResource('Entitymanagerfactory');
    }

    public function getAllPosts()
    {
        return $this->_em->getRepository('Blog_Model_Post')->findAll();
    }

    public function getPostBy($postId)
    {
        return $this->_em->find('Blog_Model_Post', $postId);
    }

    public function savePost($data, $postId = null)
    {
        if (is_null($postId)) {
            $post = new Blog_Model_Post();
        } else {
            $post = $this->_em->find('Blog_Model_Post', $postId);
        }
        $post->setTitle($data['title']);
        $post->setContent($data['content']);

        $this->_em->persist($post);
        return $this->_em->flush();
    }

    public function deletePostBy($postId)
    {
        $post = $this->_em->find('Blog_Model_Post', $postId);

        $this->_em->remove($post);
        return $this->_em->flush();
    }

    public function addComment($data, $postId)
    {
        $post = $this->_em->find('Blog_Model_Post', $postId);
        $comment = new Blog_Model_Comment();
        $comment->setAuthor($data['author']);
        $comment->setAuthorEmail($data['author_email']);
        $comment->setAuthorUrl($data['author_url']);
        $comment->setContent($data['content']);
        $comment->setAuthorIp($data['ip']);
        $comment->setApproved(1);
        $comment->setPost($post);

        $post->addComments($comment);

        $this->_em->persist($post);
        return $this->_em->flush();
    }

    public function insertDummyPosts($limit = 20)
    {
        for ($i = 0; $i < $limit; $i++) {
            // create new default article
            $defaultArticle = new Blog_Model_Post();

            $content = 'Nam rutrum posuere purus; ac consequat augue commodo eu. Sed gravida lacus at mi semper faucibus. Proin ac orci eros, in convallis purus. Ut mollis neque convallis leo rutrum sit amet accumsan turpis varius. Ut non sem non lacus egestas pharetra. Suspendisse lorem tortor, laoreet ut sollicitudin eget; lacinia at tellus. Ut dignissim, velit id congue interdum, magna enim luctus neque, eu aliquam dolor nisl nec sem. Phasellus dapibus sagittis massa eu bibendum. Aenean tincidunt elit ac tellus tristique aliquam. Nunc vehicula neque eu ipsum convallis quis sollicitudin felis aliquam. Morbi vulputate fermentum dui nec eleifend. Donec nisi mi, pretium et molestie ut; consequat eget arcu. Vivamus a felis sapien! Suspendisse suscipit; odio volutpat porta porttitor, leo nibh mattis orci, et euismod libero urna ac neque. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In aliquet; mi ac rhoncus auctor, risus turpis dictum mi, id malesuada ipsum leo iaculis enim. Vivamus fringilla arcu nec nunc convallis ac tristique nisi suscipit. Vestibulum mattis lectus non nulla convallis cursus!
                Mauris lobortis metus justo, ac sagittis urna. Nunc ac nisi neque. Nam at leo libero. Phasellus orci dui; adipiscing vitae blandit eu, egestas sed metus. Praesent vitae orci nunc, et ultrices velit. Vivamus placerat ullamcorper risus, ut vulputate magna consectetur fringilla. Maecenas molestie, est elementum pretium aliquet, felis eros laoreet sapien; in pharetra ante sapien tristique erat. Sed in turpis lacinia lorem ultrices convallis. Mauris suscipit dignissim velit vel laoreet. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Fusce ullamcorper mauris sed metus commodo et feugiat sapien ultrices.
                Donec convallis massa et nisi egestas et vulputate nibh suscipit. Vivamus porttitor magna a dolor dictum ultricies. Quisque in quam massa. Cras sed feugiat magna. Donec a ante erat. Morbi nunc quam, vehicula in varius imperdiet, mollis vitae lectus. Mauris quis bibendum ante! Sed tellus libero, placerat a auctor a, egestas non nisi. Aliquam diam odio, laoreet vitae sollicitudin vel, posuere eu nibh. Fusce id nisl risus, sed suscipit velit. Maecenas cursus ultricies vestibulum. Proin felis nibh, scelerisque ac ullamcorper non, convallis ac risus!' . time()
            ;

            $title = substr(str_shuffle($content), -50);
            $defaultArticle->setTitle(trim($title));
            $defaultArticle->setContent(str_shuffle($content));
            $this->_em->persist($defaultArticle);
        }
        $this->_em->flush();
    }

}