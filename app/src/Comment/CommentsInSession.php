<?php
namespace erna13\Comment;

class CommentsInSession implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    public function add($comment, $key)
    {
        $comments = $this->session->get('comments', []);
        $comments[$key][] = $comment;
        $this->session->set('comments', $comments);
    }
    public function deleteById($id, $key)
    {
        $comments = $this->session->get('comments', []);
        unset($comments[$key][$id]);
        $this->session->set('comments', $comments);
    }
    public function update($id, $key, $newComment)
    {
        $allComments = $this->session->get('comments', []);
        $allComments[$key][$id] = $newComment;
        $this->session->set('comments', $allComments);

    }

    public function findAll($key)
    {
        $allComments = $this->session->get('comments', []);
        if (array_key_exists($key, $allComments)) {
            return $allComments[$key];
        }

    }

    public function findOne($id, $key)
    {
        $allComments = $this->session->get('comments', []);
        return $allComments[$key][$id];
    }

    public function deleteAll($key)
    {
        $allComments = $this->session->get('comments', []);
        unset($allComments[$key]);
        $this->session->set('comments', $allComments);
    }
}
