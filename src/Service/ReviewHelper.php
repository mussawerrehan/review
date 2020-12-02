<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ReviewHelper
 * @package App\Service
 */
class ReviewHelper
{
    private $doctrineHelper;

    private $tokenHelper;
    public function __construct(
        DoctrineHelper $doctrineHelper,
        TokenHelper $tokenHelper    )
    {
        $this->doctrineHelper = $doctrineHelper;
        $this->tokenHelper = $tokenHelper;
    }
    public function updateReview($request , $review)
    {
        if($request->request->get('star'))
            $review->setStar($request->request->get('star'));
        if($description = $request->request->get('description'))
            $review->setDescription($request->request->get('description'));
        $this->doctrineHelper->AddToDb($review);
    }

    public function delete($review,$request)
    {
        $user = $this->tokenHelper->getUserFromToken($request);

        if ($review->getUser() == $user)
        {
            $this->doctrineHelper->DeleteFromDb($review);
        }else{
            return new JsonResponse(['Failed' => 'You are not allowed to delete this review']);
        }
        return new JsonResponse(['Success' => 'Record Deleted']);
    }

}