<?php

namespace CODOF\Service;

use CODOF\DTO\BadgeDTO;
use CODOF\DTO\UserBadgeDTO;
use CODOF\User\CurrentUser\CurrentUser;

class BadgeService
{
    private /*\PDO*/
        $db;

    public function __construct()
    {
        $this->db = \DB::getPDO();
    }

    public function editBadge($badgeId, $badgeName, $badgeDescription, $badgeLocation, $badgeOrder)
    {
        $parameters = ['id' => $badgeId, 'name' => $badgeName, 'description' => $badgeDescription, 'order' => $badgeOrder];
        if ($badgeLocation == null) {
            $query = "UPDATE codo_badges SET name=:name,description=:description,view_order=:order
                    WHERE id=:id";
        } else {
            $parameters['location'] = $badgeLocation;
            $query = "UPDATE codo_badges SET name=:name,description=:description,location=:location,view_order=:order
                    WHERE id=:id";
        }
        $obj = $this->db->prepare($query);
        $obj->execute($parameters);
    }

    public function deleteBadgeFromSystem(int $badgeId)
    {
        $query = "DELETE FROM codo_user_badges WHERE badge_id=:badge_id";
        $obj = $this->db->prepare($query);
        $obj->execute(['badge_id' => $badgeId]);
        $query = "DELETE FROM codo_badges WHERE id=:badge_id";
        $obj = $this->db->prepare($query);
        $obj->execute(['badge_id' => $badgeId]);
    }

    public function deleteAllBadgesForUser(int $userId)
    {
        $query = "DELETE FROM codo_user_badges WHERE user_id=:user_id";
        $obj = $this->db->prepare($query);
        $obj->execute(['user_id' => $userId]);
    }

    public function deleteBadgeForUser(int $badgeId, int $userId)
    {
        $query = "DELETE FROM codo_user_badges WHERE badge_id=:badgeId AND user_id=:user_id";
        $obj = $this->db->prepare($query);
        $obj->execute(['user_id' => $userId, 'badge_id' => $badgeId]);
    }

    public function addBadge($badgeName, $badgeDescription, $badgeLocation, $badgeOrder)
    {
        $query = "INSERT INTO codo_badges (name,description,location,view_order)
                    VALUES (:name,:description,:location,:view_order)";
        $obj = $this->db->prepare($query);
        $obj->execute(['name' => $badgeName, 'description' => $badgeDescription, 'location' => $badgeLocation, 'view_order' => $badgeOrder]);
    }

    public function getAllBadges()
    {
        $badgeDTOList = [];
        $query = "SELECT * FROM codo_badges ORDER BY view_order";
        $obj = $this->db->prepare($query);
        $obj->execute();
        $result = $obj->fetchAll();
        if ($result) {
            foreach ($result as $badgeRecord) {
                $badgeDTO = new BadgeDTO($badgeRecord['id'], $badgeRecord['name'],
                    $badgeRecord['description'], $badgeRecord['location']);
                $badgeDTO->order = $badgeRecord['view_order'];
                $badgeDTOList[] = $badgeDTO;
            }
        }
        return $badgeDTOList;
    }

    public function getAllBadgesWithRewardedForUser(int $uid)
    {
        $badgeDTOList = [];
        $query = "SELECT b.*, ub.user_id FROM codo_badges b 
                    LEFT JOIN codo_user_badges ub ON b.id=ub.badge_id AND ub.user_id=:uid 
                    ORDER BY b.view_order";
        $obj = $this->db->prepare($query);
        $obj->execute(["uid" => $uid]);
        $result = $obj->fetchAll();
        if ($result) {
            foreach ($result as $badgeRecord) {
                $badgeDTOList[] = new UserBadgeDTO($badgeRecord['id'], $badgeRecord['name'],
                    $badgeRecord['description'], $badgeRecord['location'], $badgeRecord['user_id'] != null);
            }
        }
        return $badgeDTOList;
    }

    public function getRewardedBadgesForUsers(array $uids)
    {
        $badgeDTOList = [];
        $placeholders = str_repeat('?,', count($uids) - 1) . '?';
        $query = "SELECT b.*, ub.user_id FROM codo_badges b 
                    INNER JOIN codo_user_badges ub ON b.id=ub.badge_id 
                    WHERE ub.user_id IN ($placeholders) ORDER BY  b.view_order";
        $obj = $this->db->prepare($query);
        $obj->execute($uids);
        $result = $obj->fetchAll();
        if ($result) {
            foreach ($result as $badgeRecord) {
                if (!isset($badgeDTOList[$badgeRecord['user_id']])) {
                    $badgeDTOList[$badgeRecord['user_id']] = [];
                }
                $badgeDTOList[$badgeRecord['user_id']][] = new BadgeDTO($badgeRecord['id'], $badgeRecord['name'],
                    $badgeRecord['description'], $badgeRecord['location']);
            }
        }
        return $badgeDTOList;
    }

    public function assignBadgesToUser(array $addBadgeIds, array $removeBadgeIds, int $uid)
    {
        if (count($addBadgeIds) > 0) {
            $rows = [];
            foreach ($addBadgeIds as $badgeId) {
                $rows[] = [
                    "badge_id" => $badgeId,
                    "user_id" => $uid,
                    "rewarded_date" => time(),
                    "rewarded_by" => CurrentUser::id()
                ];
            }
            \DB::table('codo_user_badges')->insert($rows);
        }
        if (count($removeBadgeIds) > 0) {
            \DB::table('codo_user_badges')->whereIn('badge_id', $removeBadgeIds)->delete();
        }
    }

    /**
     * This function is called from Cron "on_cron_badge_notify"
     * It adds any rewarded badges into notifications table
     * @param $cron
     */
    public function addBadgeNotifications($cron)
    {
        $notifier = new \CODOF\Forum\Notification\Notifier();
        $lastRunTime = $cron['cron_last_run'];

        $query = "SELECT b.name AS badge_name, ub.user_id, ub.rewarded_by, u.name AS rewarded_by_name, u.avatar
                    FROM codo_user_badges ub 
                    INNER JOIN codo_badges b ON b.id=ub.badge_id
                    INNER JOIN codo_users u ON u.id=ub.rewarded_by
                    WHERE ub.rewarded_date>?";
        $obj = $this->db->prepare($query);
        $obj->execute([$lastRunTime]);
        $result = $obj->fetchAll();

        if ($result) {
            foreach ($result as $record) {
                $badgeData = array(
                    "label" => _t("Badge earned"),
                    "link" => 'user/profile',
                    "notifyFrom" => $record['rewarded_by'],
                    "actor" => [
                        "username" => $record['rewarded_by_name'],
                        "avatar" => $record['avatar']
                    ],
                    "notification" => "%actor% rewarded you the badge <b>%badgeName%</b>",
                    "bindings" => ["badgeName" => $record['badge_name']]
                );
                $notifier->directNotify('badge_earned', $badgeData, $record['user_id']);
            }
        }
    }
}