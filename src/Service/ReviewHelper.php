<?php


namespace App\Service;


use App\Repository\ReviewRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ReviewHelper
 * @package App\Service
 */
class ReviewHelper
{
    /**
     * @var DoctrineHelper
     */
    private $doctrineHelper;

    /**
     * @var TokenHelper
     */
    private $tokenHelper;

    private $reviewRepository;

    /**
     * ReviewHelper constructor.
     * @param DoctrineHelper $doctrineHelper
     * @param TokenHelper $tokenHelper
     */
    public function __construct(
        DoctrineHelper $doctrineHelper,
        TokenHelper $tokenHelper,
        ReviewRepository $reviewRepository)
    {
        $this->doctrineHelper = $doctrineHelper;
        $this->tokenHelper = $tokenHelper;
        $this->reviewRepository = $reviewRepository;
    }

    /**
     * @param $request
     * @param $review
     */
    public function updateReview($request , $review)
    {
        if($request->request->get('star'))
            $review->setStar($request->request->get('star'));
        if($description = $request->request->get('description'))
            $review->setDescription($request->request->get('description'));
        $this->doctrineHelper->AddToDb($review);
    }
    public function findAll($user)
    {
        return $this->reviewRepository->findWithAverage($user);
    }

    public function findForUser($user)
    {
        return $this->reviewRepository->findOrderedListForUser($user);
    }

    /**
     * @param $review
     * @param $request
     * @return JsonResponse
     */
    public function delete($review, $request)
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