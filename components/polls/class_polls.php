<?php


/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 17.02.2016
 * Time: 13:08
 */
class Polls{

    private static $db;

    function __construct(){
        self::$db = Registry::get("DataBase");
    }

    public function getPolls()
    {

        $sql = "SELECT * FROM poll_questions ORDER BY startdate DESC";
        $row = self::$db->fetch_all($sql);

        return ($row) ? $row : 0;
    }

    public function getPollOptions($itemid)
    {

        $sql = "SELECT * FROM poll_options WHERE question_id = '" . $itemid . "' ORDER BY position";
        $row = self::$db->fetch_all($sql);

        return ($row) ? $row : 0;
    }

    public function showPollResults($poll_id)
    {
        $sql = Registry::get("Database")->query("SELECT COUNT(*) as totalvotes"
            . "\n FROM poll_votes"
            . "\n WHERE option_id"
            . "\n IN(SELECT id FROM poll_options WHERE question_id='" . (int)$poll_id . "')");
        while ($row = self::$db->fetch($sql))
            $total = $row->totalvotes;
        $query = self::$db->query("SELECT poll_options.id, poll_options.value, COUNT(*) as votes"
            . "\n FROM poll_votes, poll_options"
            . "\n WHERE poll_votes.option_id =  poll_options.id"
            . "\n AND poll_votes.option_id"
            . "\n IN(SELECT id FROM poll_options WHERE question_id='" . (int)$poll_id . "')"
            . "\n GROUP BY poll_options.option_id ORDER BY position ");
        $display = '';
        while ($row = self::$db->fetch($query))
        {
            $percent = round(($row->votes * 100) / $total);
            $display .= "<div class=\"option\">" . $row->{'value' . Lang::$lang} . " (<em>" . $percent . "%, " . $row->votes . " " . Lang::$word->_PLG_PL_VOTES . "</em> )\n";
            $display .= "<div class=\"option-bar-out\"><div class=\"optionbar\" style=\"width:" . $percent . "%;\"></div></div></div>\n";
        }
        $display .= "<p>" . Lang::$word->_PLG_PL_TOTAL . " " . $total . "</p>\n";

        return $display;
    }

    public function showPollQuestion()
    {

        $sql = "SELECT id, question  FROM poll_questions WHERE status = 1";
        $row = self::$db->first($sql);

        print "<div class=\"question\" >" . $row->question . "</div>\n";
        $id = intval($row->id);

        if (isset($_GET["result"]) == 1 || isset($_COOKIE["CMSPRO_voted" . $id]) == 'yes') {
            print $this->getPollResults($id);
            exit;
        } else {
            $sql = self::$db->query("SELECT id, value FROM poll_options WHERE question_id={$id} ORDER BY position");
            if (self::$db->numrows($sql)) {
                print "<div id=\"formcontainer\" class=\"wojo form\">\n";
                print "<form method=\"post\" id=\"pollform\">\n";
                print "<input type=\"hidden\" name=\"pollid\" value=\"" . $id . "\" />\n";
                while ($row = self::$db->fetch($sql)) {
                    print "<div class=\"option-bar\"><label class=\"radio\"><input type=\"radio\" name=\"poll\" value=\"" . $row->id . "\" id=\"option-" . $row->id . "\"/>\n";
                    print "<i></i>" . $row->value  . "</label></div>\n";
                }
                print "<div class=\"buttons\"><a class=\"positive button votenow\">Овоз</a>\n";
                print "<a href=\"/modules/mod_polls/poll.php?result=1\" id=\"viewresult\" class=\"secondary button\">Натижалар</a></div>\n";
                print "</form>\n";
                print "</div>";
            }
        }
    }

    public function addPoll()
    {
        Core::checkPost('question', "Poll Question");
        $question = array_filter($_POST['value'], 'strlen');
        if (empty($question))
            Core::$msgs['value'] ="Poll Options";

        if (empty(Core::$msgs)) {
            $qdata = array(

                'question'  => sanitize($_POST['question']),
                'startdate' => "NOW()",
                'enddate' => "NOW()",
                'lang' => Registry::get("Config")->lang,
                'ordering' => $_POST['ordering'],
                'status' => intval($_POST['status'])
            );

            if ($qdata['status'] == 1) {
                $status['status'] = "DEFAULT(status)";
                self::$db->update("poll_questions", $status);
            }

            self::$db->insert("poll_questions", $qdata);
            $lastID = self::$db->insertid();
            foreach ($_POST['value'] as $key => $val) {

                $data = array(
                    'value' . Lang::$lang => sanitize($val),
                    'question_id' => $lastID,
                    'position' => $key
                );
                $res = self::$db->insert("poll_options", $data);
            }

//            if ($res) {
//                Security::writeLog(Lang::$word->_PLG_PL_ADDED, "", "no", "plugin");
//                $json['type'] = 'success';
//                $json['message'] = Filter::msgOk(Lang::$word->_PLG_PL_ADDED, false);
//            } else {
//                $json['type'] = 'success';
//                $json['message'] = Filter::msgAlert(Lang::$word->_SYSTEM_PROCCESS, false);
//            }
//            print json_encode($json);

        }
//        else {
//            $json['message'] = Filter::msgStatus();
//            print json_encode($json);
//        }
    }

    public function updatePoll()
    {

        Core::checkPost('question' ,'Poll Question');

        if (!array_filter($_POST['value']))
            Core::checkPost('value', 'Poll Options');

        if (empty(Core::$msgs)) {
            $qdata = array(
                'question'  => sanitize($_POST['question']),
                'startdate' => "NOW()",
                'enddate' => "NOW()",
                'lang' => Registry::get("Config")->lang,
                'ordering' => $_POST['ordering'],
                'status' => intval($_POST['status'])
            );

            if ($qdata['status'] == 1) {
                $status['status'] = "DEFAULT(status)";
                self::$db->update("poll_questions", $status);
            }

            self::$db->update("poll_questions", $qdata, "id=" . Core::$post['itemid']);

            foreach ($_POST['value'] as $key => $val) {
                $data = array(
                    'value' => sanitize($val),
                    'question_id' => Core::$post['itemid'],
//                    'position' => intval($key)
                );
            $res = self::$db->update('poll_options', $data, "id=" . $key);
            }

//
//            if ($res) {
//                Security::writeLog(Lang::$word->_PLG_PL_UPDATED, "", "no", "plugin");
//                $json['type'] = 'success';
//                $json['message'] = Filter::msgOk(Lang::$word->_PLG_PL_UPDATED, false);
//            } else {
//                $json['type'] = 'success';
//                $json['message'] = Filter::msgAlert(Lang::$word->_SYSTEM_PROCCESS, false);
//            }
//            print json_encode($json);

        } else {
//            $json['message'] = Filter::msgStatus();
//            print json_encode($json);
        }
    }

    private function countTotalVotes($id)
    {

        $sql = "SELECT COUNT(*) as totalvotes FROM poll_votes "
            . " \n WHERE option_id IN(SELECT id FROM poll_options WHERE question_id=" . (int)$id . ")";

        $row = self::$db->first($sql);
        return $row->totalvotes;
    }

    public function getPollResults($id)
    {

        $query = "SELECT poll_options.id, poll_options.value, COUNT(*) as votes FROM poll_votes, poll_options"
            . " \n WHERE poll_votes.option_id=poll_options.id"
            . " \n AND poll_votes.option_id IN(SELECT id FROM poll_options WHERE question_id='" . (int)$id . "')"
            . " \n GROUP BY poll_votes.option_id";

        $html = '';
        $showall = self::$db->fetch_all($query);
        $total = $this->countTotalVotes($id);

        foreach ($showall as $row) {
            $percent = round(($row->votes * 98) / $total);
            $html .= "<div class=\" success active striped progress\">
			  <div class=\"bar\" style=\"width:" . $percent . "%;\">" . $percent . "%</div></div>\n";
            $html .= "<div class=\"option\">" . $row->value . " (<em>" . $percent . "%, " . $row->votes . " овоз </em> )</div>\n";
        }
        $html .= "<div class=\"positive totalvote\">Жами овозлар " . $total . "</div>\n";

        return $html;
    }

    public function updatePollResult()
    {

        $sql = self::$db->query("SELECT * FROM poll_options WHERE id='" . intval($_POST["poll"]) . "'");
        if (self::$db->numrows($sql)) {
            $data['option_id'] = intval($_POST["poll"]);
            $data['voted_on'] = "NOW()";
            $data['ip'] = sanitize($_SERVER['REMOTE_ADDR']);

            self::$db->insert("poll_votes", $data);
            if (self::$db->affected()) {
                setcookie("BPACMS_voted" . intval($_POST['pollid']), 'yes', time() + 86400 * 300);
                print "<div class=\"wojo success message\">Thanks for voting</div>";
            }
        }

    }
}
?>