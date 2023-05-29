<?php


namespace Controller\Ajax\user;


use CODOF\DTO\Response;
use CODOF\Service\BadgeService;
use CODOF\User\User;
use Exception;

class BadgeController
{
    private /*BadgeService*/
        $badgeService;

    public function __construct()
    {
        $this->badgeService = new BadgeService();
    }

    private function checkPrivilegedAccess()
    {
        $user = User::get();
        if (!$user->hasRoleId(ROLE_ADMIN) && !$user->hasRoleId(ROLE_MODERATOR)) {
            throw new Exception("Not enough permission to perform this action");
        }
    }

    public function deleteBadgeFromSystem()
    {
        $this->checkPrivilegedAccess();
        $badgeId = $_POST['badgeId'];
        $this->badgeService->deleteBadgeFromSystem($badgeId);
        return new Response();
    }

    public function editBadge()
    {
        $this->checkPrivilegedAccess();
        $badgeId = $_POST['badgeId'];
        $badgeName = $_POST['badgeName'];
        $badgeDescription = $_POST['badgeDescription'];
        $badgeOrder = $_POST['badgeOrder'] == "" ? 0 : $_POST['badgeOrder'];
        $fileInfo = ['name' => null];
        if (isset($_FILES['badgeImage'])) {
            $badgeImage = $_FILES['badgeImage'];
            if (!\CODOF\File\Upload::valid($badgeImage) or !\CODOF\File\Upload::not_empty($badgeImage) or !\CODOF\File\Upload::type($badgeImage, array('jpg', 'jpeg', 'png', 'gif', 'svg', 'pjpeg', 'bmp'))) {
                throw new \Exception("invalid badge image");
            }
            $fileInfo = \CODOF\File\Upload::save($badgeImage, NULL, DATA_PATH . 'assets/img/badges', 0777);
        }
        $this->badgeService->editBadge($badgeId, $badgeName, $badgeDescription, $fileInfo['name'], $badgeOrder);
        return new Response();
    }

    public function addBadge()
    {
        $this->checkPrivilegedAccess();
        $badgeName = $_POST['badgeName'];
        $badgeDescription = $_POST['badgeDescription'];
        $badgeImage = $_FILES['badgeImage'];
        $badgeOrder = $_POST['badgeOrder'] == "" ? 0 : $_POST['badgeOrder'];
        if (!\CODOF\File\Upload::valid($badgeImage) OR !\CODOF\File\Upload::not_empty($badgeImage) OR !\CODOF\File\Upload::type($badgeImage, array('jpg', 'jpeg', 'png', 'gif', 'svg', 'pjpeg', 'bmp'))) {
            throw new \Exception("invalid badge image");
        }
        $fileInfo = \CODOF\File\Upload::save($badgeImage, NULL, DATA_PATH . 'assets/img/badges', 0777);
        $this->badgeService->addBadge($badgeName, $badgeDescription, $fileInfo['name'], $badgeOrder);
        return new Response();
    }

    /**
     * @param int $uid
     * @return Response
     * @throws Exception
     */
    public function assignBadgesToUser(int $uid)
    {
        $this->checkPrivilegedAccess();
        $addBadgeIds = isset($_POST['addBadgeIds']) ? $_POST['addBadgeIds'] : [];
        $removeBadgeIds = isset($_POST['removeBadgeIds']) ? $_POST['removeBadgeIds'] : [];
        $this->badgeService->assignBadgesToUser($addBadgeIds, $removeBadgeIds, $uid);
        return new Response();
    }

    public function listBadges()
    {
        $badgeList = ($this->badgeService->getAllBadges());
        return (new Response())->withData($badgeList);
    }

    /**
     * Gets all badges available with also saying which badges are rewarded for passed user
     * Note: Privileged access not checked since it can be called by normal user too
     * @param int $uid
     * @return Response
     */
    public function listBadgesWithRewardedForUser(int $uid)
    {
        $badgeList = ($this->badgeService->getAllBadgesWithRewardedForUser($uid));
        return (new Response())->withData($badgeList);
    }
}