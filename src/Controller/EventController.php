<?php

namespace App\Controller;

use App\Services\BitcoinService;
use App\Services\RandomService;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as SWG;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @Route("/api")
 */
class EventController extends AbstractController
{
    const MERCURE_TOPIC = 'http://example.com/events';

    const FOLLOW_EVENT_TYPE = 'follow';
    const SUBSCRIBE_EVENT_TYPE = 'subscribe';
    const DONATION_EVENT_TYPE = 'donation';
    const RAID_EVENT_TYPE = 'raid';
    const MUSIC_EVENT_TYPE = 'music';

    /**
     * @Route("/follow", name="api_follow", methods={"POST"})
     *
     * @SWG\Parameter(
     *     name="username",
     *     in="query",
     *     description="Username of the follower."
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Return the mercure event id.",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Property(property="uuid", type="string", example="123-abc")
     *     )
     * )
     * @SWG\Tag(name="Events")
     * @Security(name="Bearer")
     */
    public function follow(HubInterface $hub, Request $request): Response
    {
        $params = [
            'type' => self::FOLLOW_EVENT_TYPE,
            'username' => $request->get('username'),
        ];

        $result = $hub->publish(new Update(self::MERCURE_TOPIC, json_encode($params)));

        return $this->json(['uuid' => $result]);
    }

    /**
     * @Route("/subscribe", name="api_subscribe", methods={"POST"})
     *
     * @SWG\Parameter(
     *     name="username",
     *     in="query",
     *     description="Username of the subscriber."
     * )
     * @SWG\Parameter(
     *     name="isPrime",
     *     in="formData",
     *     description="Subscription type is 'Prime'."
     * )
     * @SWG\Parameter(
     *     name="isGift",
     *     in="query",
     *     description="Subscription type is a gift."
     * )
     * @SWG\Parameter(
     *     name="recipient",
     *     in="query",
     *     description="Is a gift for ?"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Return the mercure event id.",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Property(property="uuid", type="string", example="123-abc")
     *     )
     * )
     * @SWG\Tag(name="Events")
     * @Security(name="Bearer")
     */
    public function subscribe(HubInterface $hub, Request $request): Response
    {
        $params = [
            'type' => self::SUBSCRIBE_EVENT_TYPE,
            'username' => $request->get('username'),
            'isPrime' => $request->get('isPrime'),
            'isGift' => $request->get('isGift'),
            'recipient' => $request->get('recipient'),
        ];

        $result = $hub->publish(new Update(self::MERCURE_TOPIC, json_encode($params)));

        return $this->json(['uuid' => $result]);
    }

    /**
     * @Route("/donation", name="api_donation", methods={"POST"})
     *
     * @SWG\Parameter(
     *     name="username",
     *     in="query",
     *     description="Username of the donator."
     * )
     * @SWG\Parameter(
     *     name="amount",
     *     in="query",
     *     description="Amount of the donation."
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Return the mercure event id.",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Property(property="uuid", type="string", example="123-abc")
     *     )
     * )
     * @SWG\Tag(name="Events")
     * @Security(name="Bearer")
     */
    public function donation(HubInterface $hub, Request $request): Response
    {
        $params = [
            'type' => self::DONATION_EVENT_TYPE,
            'username' => $request->get('username'),
            'amount' => $request->get('amount'),
        ];

        $result = $hub->publish(new Update(self::MERCURE_TOPIC, json_encode($params)));

        return $this->json(['uuid' => $result]);
    }

    /**
     * @Route("/raid", name="api_raid", methods={"POST"})
     *
     * @SWG\Parameter(
     *     name="username",
     *     in="query",
     *     description="Username of the raid initiator."
     * )
     * @SWG\Parameter(
     *     name="viewers",
     *     in="query",
     *     description="Count of viewers in the raid."
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Return the mercure event id.",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Property(property="uuid", type="string", example="123-abc")
     *     )
     * )
     * @SWG\Tag(name="Events")
     * @Security(name="Bearer")
     */
    public function raid(HubInterface $hub, Request $request): Response
    {
        $params = [
            'type' => self::RAID_EVENT_TYPE,
            'username' => $request->get('username'),
            'viewers' => $request->get('viewers'),
        ];

        $result = $hub->publish(new Update(self::MERCURE_TOPIC, json_encode($params)));

        return $this->json(['uuid' => $result]);
    }

    /**
     * @Route("/music", name="api_music", methods={"POST"})
     *
     * @SWG\RequestBody(
     *     required=true,
     *     @SWG\JsonContent(
     *         example={
     *             "author": "Sylvain D",
     *             "song": "The silence",
     *             "albumImg": "https://www.formica.com/fr-fr/-/media/formica/emea/products/swatch-images/f2253/f2253-swatch.jpg",
     *             "noSound": false
     *         },
     *         @SWG\Schema (
     *              type="object",
     *              @SWG\Property(property="author", required=true, description="Author of the current music", type="string"),
     *              @SWG\Property(property="song", required=true, description="Title of the current music", type="string"),
     *              @SWG\Property(property="albumImg", required=true, description="Album image of the current music", type="string"),
     *              @SWG\Property(property="noSound", required=true, description="Hide/Show sound bars", type="boolean")
     *         )
     *     )
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Return the mercure event id.",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Property(property="uuid", type="string", example="123-abc")
     *     )
     * )
     * @SWG\Tag(name="Events")
     * @Security(name="Bearer")
     */
    public function music(HubInterface $hub, Request $request): Response
    {
	$body = json_decode($request->getContent(), true);

        $params = [
            'type' => self::MUSIC_EVENT_TYPE,
            'albumImg' => $body['base64'],
            'author' => $body['author'],
	    'song' => $body['song'],
	    'noSound' => $body['noSound'],
        ];

        $result = $hub->publish(new Update(self::MERCURE_TOPIC, json_encode($params)));

        return $this->json(['uuid' => $result]);
    }
}
