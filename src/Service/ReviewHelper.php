<?php


namespace App\Service;


class ReviewHelper
{
    private $doctrineHelper;
    public function __construct(DoctrineHelper $doctrineHelper)
    {
        $this->doctrineHelper = $doctrineHelper;
    }
    public function updateReview($request , $review)
    {
        if($request->request->get('star'))
            $review->setStar($request->request->get('star'));
        if($description = $request->request->get('description'))
            $review->setDescription($request->request->get('description'));
        $this->doctrineHelper->AddToDb($review);
    }

}