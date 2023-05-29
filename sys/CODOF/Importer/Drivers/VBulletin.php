<?php

/*
 * @CODOLICENSE
 */

namespace CODOF\Importer\Drivers;


use CODOF\Cron\Cron;
use CODOF\Forum\Post;

class VBulletin
{

    public $max_rows = 100;

    /**
     * Mention whether your posts table contain topic message as a post or not ?
     *
     * If it is set to true , make sure the query in get_posts() below returns
     * messages of all topics too
     *
     * Note: Importer runs faster when posts table has the message of topics
     *       but sadly not all forum systems are the same :(
     * @var boolean
     */
    public $post_has_topic = true;

    public $attachmentDir = ".";

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Table prefix
     * @var type
     */
    public function set_prefix($prefix)
    {

        define('DBPRE', $prefix);
    }


    /**
     *
     * Selects
     * category id          -> cat_id
     * category name        -> cat_name
     * category description -> cat_description
     * category order       -> cat_order
     * category parent id   -> cat_pid
     * @return type
     */
    public function get_cats()
    {

        $qry = "SELECT nodeid cat_id, title cat_name, `description` cat_description, 
                    displayorder cat_order, CASE WHEN parentid <= 2 THEN 0 ELSE parentid END AS cat_pid
                    FROM " . DBPRE . "node 
                    WHERE contenttypeid=23 AND showpublished=1 AND parentid>1";

        $res = $this->db->query($qry);
        return $res->fetchAll();
    }

    /**
     *
     * Selects
     * topic id           -> topic_id
     * topic title        -> title
     * category id        -> cat_id
     * topic created time -> topic_created
     * topic updated time -> topic_updated
     * last post id       -> last_post_id    [optional]
     * last post uid      -> last_post_uid   [optional]
     * last post name     -> last_post_name  [optional]
     * last post time     -> last_post_time  [optional]
     * user id who creaed -> uid
     * post message       -> message [Must be selected when $post_has_topic=false Otherwise OPTIONAL]
     * post id            -> post_id [Must be selected when $post_has_topic=true  Otherwise OPTIONAL]
     * @param type $start
     * @return type
     */
    public function get_topics($start)
    {
        $qry = "SELECT t.nodeid topic_id, t.title, t.parentid cat_id, t.created topic_created, t.lastupdate topic_updated,
                    t.nodeid post_id, t.userid uid, t.lastcontentid last_post_id, t.lastauthorid last_post_uid,
                    t.lastcontentauthor last_post_name, t.lastcontent last_post_time
                FROM " . DBPRE . "node AS t
                INNER JOIN " . DBPRE . "node AS c ON t.parentid=c.nodeid
                WHERE c.contenttypeid=23
                 AND t.contenttypeid NOT IN (27, 23, 24)
                 AND t.showpublished=1
                 LIMIT $this->max_rows OFFSET $start";

        $res = $this->db->query($qry);
        $result = $res->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     *
     * Selects
     * category id        -> cat_id
     * topic id           -> topic_id
     * post id            -> post_id
     * user id            -> uid
     * post message       -> message
     * post created time  -> post_created
     * post modified time -> post_modified
     * @param int $start
     * @return array
     */
    public function get_posts($start)
    {
        $qry = "SELECT n.nodeid nid, n.userid uid, n.created, n.lastupdate, n.contenttypeid,
                   p.nodeid p_nid, p.contenttypeid p_contenttypeid, 
                   gp.contenttypeid gp_contenttypeid, gp.nodeid gp_nid, gp.parentid gp_parentid,
                   t.rawtext, (
                       SELECT GROUP_CONCAT(a.filedataid, '_', fd.filesize, '_',  fd.userid , '_' , fn.nodeid, '_', a.filename SEPARATOR '--CODOF--') 
                       FROM " . DBPRE . "node fn 
                       INNER JOIN " . DBPRE . "attach a ON a.nodeid=fn.nodeid
                       INNER JOIN " . DBPRE . "filedata fd ON fd.filedataid=a.filedataid
                       WHERE fn.parentid=n.nodeid AND fn.contenttypeid=15
                   ) AS fileid
         
                FROM " . DBPRE . "node AS n
                INNER JOIN " . DBPRE . "node AS p ON n.parentid=p.nodeid
                INNER JOIN `" . DBPRE . "text` AS t ON t.nodeid=n.nodeid
                LEFT JOIN " . DBPRE . "node AS gp ON p.parentid=gp.nodeid
                LEFT JOIN " . DBPRE . "node AS ggp ON gp.parentid=ggp.nodeid
        
                WHERE p.contenttypeid NOT IN (27, 24)
                AND n.contenttypeid NOT IN (15, 23, 24, 27)
                AND n.description IS NOT NULL
                AND n.showpublished=1  
                LIMIT $this->max_rows OFFSET $start";

        $res = $this->db->query($qry);
        $result = $res->fetchAll(\PDO::FETCH_ASSOC);

        $posts = [];
        $imports = [];
        foreach ($result as $res) {

            $files = [];
            if ($res['fileid'] != null) {
                $attachments = explode("--CODOF--", $res['fileid']);
                foreach ($attachments as $attachment) {
                    $parts = explode("_", $attachment, 5);
                    $attachId = $parts[0];
                    $fileSize = $parts[1];
                    $userId = $parts[2];
                    $nodeId = $parts[3];
                    $fileName = $parts[4];
                    $source = $this->attachmentDir . join("/", str_split($userId)) . "/" . $attachId . ".attach";
                    $hashes = Post::saveAttachmentInDB(array(
                        "name" => $fileName,
                        "size" => $fileSize
                    ));
                    $storedHash = $hashes[1];
                    $destination = DATA_PATH . \CODOF\Util::get_opt('forum_attachments_path') . '/' . $storedHash;
                    $imports[] = array(
                        "data" => json_encode(["source" => $source, "destination" => $destination, "type" => "attachment"])
                    );

                    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $files[] = array(
                        "hash" => $storedHash,
                        "name" => $fileName,
                        "nodeId" => $nodeId,
                        "type" => "attachment",
                        "isImage" => in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'pjpeg'])
                    );
                }
            }

            if ($res['contenttypeid'] == 25) {
                $qry = "SELECT p.filedataid, fd.filesize, fd.userid, fd.extension, n.nodeid, n.parentid 
                FROM node n 
                    INNER JOIN photo p ON p.nodeid=n.nodeid 
                    INNER JOIN filedata fd ON fd.filedataid=p.filedataid 
                WHERE n.parentid=" . $res['nid'];

                $galleryRes = $this->db->query($qry);
                $photos = $galleryRes->fetchAll(\PDO::FETCH_ASSOC);

                foreach ($photos as $photo) {
                    $fileName = $photo['filedataid'] . "." . $photo['extension'];
                    $source = $this->attachmentDir . join("/", str_split($photo['userid'])) . "/" . $photo['filedataid'] . ".attach";
                    $hashes = Post::saveAttachmentInDB(array(
                        "name" => $fileName,
                        "size" => $photo['filesize']
                    ));
                    $storedHash = $hashes[1];
                    $destination = DATA_PATH . \CODOF\Util::get_opt('forum_attachments_path') . '/' . $storedHash;
                    $imports[] = array(
                        "data" => json_encode(["source" => $source, "destination" => $destination, "type" => "gallery"])
                    );

                    $files[] = array(
                        "hash" => $storedHash,
                        "name" => $fileName,
                        "nodeId" => $photo['nodeid'],
                        "type" => "gallery",
                        "isImage" => true
                    );
                }
            }

            $res['files'] = $files;

            if ($res['p_contenttypeid'] == 23) {
                // is topic
                $cat_id = $res['p_nid'];
                $topic_id = $res['nid'];
                $post_id = $res['nid'];

            } else {
                if ($res['gp_contenttypeid'] == 23) {
                    // first level post
                    $cat_id = $res['gp_nid'];
                    $topic_id = $res['p_nid'];
                    $post_id = $res['nid'];
                } else {
                    // second level post i.e a comment to a post
                    $cat_id = $res['gp_parentid'];
                    $topic_id = $res['gp_nid'];
                    $post_id = $res['nid'];
                }
            }

            $posts[] = array(
                "cat_id" => $cat_id,
                "topic_id" => $topic_id,
                "post_id" => $post_id,
                "uid" => $res['uid'],
                "message" => $res['rawtext'],
                "post_created" => $res['created'],
                "post_modified" => $res['lastupdate'],
                "files" => $res['files']
            );
        }

        \DB::table(PREFIX . 'codo_import_data')->insert($imports);

        return $posts;
    }

    /**
     * Selects
     * user id              -> id
     * username             -> username
     * nickname             -> name
     * password             -> pass
     * email                -> mail
     * forum signature      -> signature
     * user created time    -> created
     * user last login time -> last_access
     * user status          -> status
     * user avatar url      -> avatar
     * user role id         -> rid [OPTIONAL]
     * @param type $start
     * @return type
     */
    public function get_users($start)
    {

        $qry = "SELECT u.userid id, u.username, u.username AS name, u.token pass, u.email mail,
                c.signature, u.joindate created, u.lastvisit last_access,  u.reputation, 1 AS status,
                a.filedata, a.filename AS avatar
                FROM `" . DBPRE . "user` u
                INNER JOIN " . DBPRE . "usertextfield c ON u.userid=c.userid
                LEFT JOIN " . DBPRE . "customavatar a ON a.userid=u.userid
                LIMIT $this->max_rows OFFSET $start";

        $res = $this->db->query($qry);
        $result = $res->fetchAll(\PDO::FETCH_ASSOC);
        //var_dump($result);
        return $result;
    }


    /**
     * Get the userid by email
     * This is used pre-import to check if admin account email address
     * given is correct or not
     *
     * @param type $mail
     */
    public function get_user_by_mail($mail)
    {

        $qry = 'SELECT userid AS uid FROM ' . DBPRE . 'user WHERE email=?';
        $obj = $this->db->prepare($qry);

        $obj->execute(array($mail));
        $res = $obj->fetch();

        if (!empty($res)) {

            return $res['uid'];
        }

        return false;
    }

    /**
     * Before an user is inserted we can do some preprocessing.
     * For eg. Here we copy the BLOB from vbulletin database to codoforum filesystem.
     * This BLOB is the custom profile pic of the user
     * @param $user
     * @return mixed
     */
    public function preprocess_user($user)
    {
        if ($user['avatar'] == null) return;
        file_put_contents(AVATAR_PATH . 'icons/' . $user['avatar'], $user['filedata']);
        file_put_contents(AVATAR_PATH . $user['avatar'], $user['filedata']);
    }

    public function modify_posts($post)
    {

        $post['imessage'] = $this->replaceGarbledCharacters($post['imessage']);
        $post['omessage'] = $this->replaceGarbledCharacters($post['omessage']);

        foreach ($post['files'] as $file) {
            $id = $file['nodeId'];
            $hash = $file['hash'];
            $name = $file['name'];
            $codo_gallery_container = $file['type'] == 'gallery' ? "codo_gallery_container" : "";

            if ($file['isImage']) {
                $imessage = "![$hash](serve/attachment&path=$hash)";
                $omessage = "<a title=\"Click to view full size image\" class=\"codo_lightbox_container $codo_gallery_container\" href=\"CODOF_RURI_" . UID . "_serve/attachment&amp;path=$hash\" target=\"_blank\" rel=\"nofollow\"><img src=\"CODOF_RURI_" . UID . "_serve/attachment/preview&amp;path=$hash\"></a> ";
            } else {
                $imessage = "[$hash](serve/attachment&path=$hash)";
                $omessage = "<a href=\"CODOF_RURI_" . UID . "_serve/attachment&amp;path=$hash\" title=\"Click to download file\" rel=\"nofollow\"><i class=\"glyphicon glyphicon-file\"></i>$name</a>";
            }

            $post['imessage'] = str_replace('[ATTACH]n' . $id . '[/ATTACH]', "", $post['imessage']);
            $post['omessage'] = str_replace('[ATTACH]n' . $id . '[/ATTACH]', "", $post['omessage']);

            $regex = '/.*"data\-attachmentid"\:.*' . $id . '.*?\[\/ATTACH\]/i';

            $iparts = explode("[ATTACH=JSON]", $post['imessage']);
            $oparts = explode("[ATTACH=JSON]", $post['omessage']);

            $mod_iparts = [];
            foreach ($iparts as $part) {
                $mod_iparts[] = preg_replace($regex, $imessage, $part);
            }

            $mod_oparts = [];
            foreach ($oparts as $part) {
                $mod_oparts[] = preg_replace($regex, $omessage, $part);
            }
            $post['imessage'] = join("[ATTACH=JSON]", $mod_iparts);
            $post['omessage'] = join("[ATTACH=JSON]", $mod_oparts);
        }

        $post['imessage'] = str_replace("[ATTACH=JSON]", "", $post['imessage']);
        $post['omessage'] = str_replace("[ATTACH=JSON]", "", $post['omessage']);
        $post['omessage'] .= "<br/>";

        return $post;
    }

    // Happens due to unintended decoding of characters
    private function replaceGarbledCharacters($message)
    {
        $message = str_replace("Â", "", $message);
        $message = str_replace("â€™", "'", $message);
        $message = str_replace("â€œ", '"', $message);
        $message = str_replace('â€“', '-', $message);
        $message = str_replace('â€', '"', $message);

        $message = str_replace("&quot;", '"', $message);
        return $message;
    }
}