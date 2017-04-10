<?php

class Matches_model extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /* This function will fetch near by users for logged in user. */

    public function getUserNearBy($user_id, $user_data, $offset = null) {
        $age_range = $user_data['user_settings']['age_range'];
        $interest = $user_data['user_settings']['interest'];
        if (!empty($interest) && $interest != null) {
            $interest = explode(",", $interest);
        }
        $latlong = explode(",", $user_data['latlong']);
        $latitude = $latlong[0];
        $longitude = $latlong[1];
        $radius = $user_data['radius'];
        $is_universal_profile = $user_data['user_settings']['is_universal_profile'];

        $idontcares = array();
        if (!empty($user_data['user_filters'])) {
            foreach ($user_data['user_filters'] as $filter) {
                if ($filter['sub_filter_name'] == "I don't care") {
                    $idontcares[] = $filter['sub_filter_id'];
                }
            }
        }
        if (!empty($idontcares))
            $idontcares = implode(",", $idontcares);
        if (!empty($user_id) && is_numeric($user_id)) {
            $subquery = "
                SELECT 
                    u.id,
                    u.userid AS insta_id,
                    u.one_liner,
                    user_name,
                    is_encrypted,
                    encrypted_username,
                    encrypted_bio,
                    encrypted_one_liner,
                    email,bio,
                    address,
                    full_name,
                    age,
                    gender,
                    work,
                    school,
                    m.media_id,
                    m.media_name AS user_profile,
                    m.media_thumb,
                    ur.requestto_id as test_rto,
                    IFNULL(m.media_type,0) AS media_type,
                    m.insta_datetime,IF(ur.relation_status IS NULL,'' ,ur.relation_status) AS relation_status,us.is_timestamps_on,u.latlong,is_universal_profile,
                        (SELECT GROUP_CONCAT(sub_filter_id) FROM user_filter WHERE userid = u.id AND is_filter_on = 1) AS sub_filter_ids,
                            us.age_range,l.id AS location_id,l.latlong AS custom_location,u.id as uid
                            FROM users AS u
                            LEFT JOIN location AS l ON l.id = u.location_id
                            LEFT JOIN media AS m ON m.id = profile_media_id
                            LEFT JOIN user_settings AS us ON us.userid = u.id
                            LEFT JOIN users_relation AS ur ON ur.requestto_id = u.id AND requestby_id = $user_id AND ur.is_blocked = 0
                            WHERE u.is_delete = 0 AND u.id != $user_id AND us.is_visibility = 1
                            AND IFNULL((SELECT relation_status FROM users_relation WHERE requestto_id = u.id AND requestby_id = $user_id ORDER BY updated_date LIMIT 1), 1) = 1
                            AND u.age >= SUBSTRING_INDEX($age_range,'-',1) AND u.age <= SUBSTRING_INDEX($age_range,'-',-1)
                            AND u.age >= 18 AND u.age <= 100 ";

            // male female and checking age range
            $length = count($interest);
            if ($length > 1) {
                $subquery .= " AND (u.gender = 'Male' OR u.gender = 'Female')  ";
            } else {
                $subquery .= " AND u.gender = '" . $interest[0] . "' ";
            }

            $query = "SELECT s.* FROM (" . $subquery . " GROUP BY u.id) AS s WHERE ";
            /* filteration */
            $wherequery = "((
                    SELECT 
                        COUNT(*) 
                    FROM 
                        user_filter 
                    WHERE 
                        is_filter_on = 1 
                    AND 
                        find_in_set(sub_filter_id,'$idontcares') 
                    AND 
                        userid = $user_id) > 0 OR 
                            (SELECT COUNT(*) 
                                FROM user_filter AS user 
                                JOIN user_filter AS parent ON parent.sub_filter_id = user.sub_filter_id 
                                AND parent.userid = $user_id 
                                AND parent.is_filter_on = 1 
                                WHERE user.userid = s.id 
                                AND user.is_filter_on = 1) > 0)";

            /* near by serched */
            $wherequery .= " AND ((((((acos(sin((" . $latitude . " *pi()/180)) * sin((SUBSTRING_INDEX(latlong,',',1)*pi()/180))+cos((" . $latitude . " *pi()/180)) * cos((SUBSTRING_INDEX(latlong,',',1)*pi()/180))* cos((( " . $longitude . " -SUBSTRING_INDEX(latlong,',',-1))*pi()/180))))*180/pi())*60*1.1515) <= " . $radius . ")))";

            if ($is_universal_profile == 1) {
                $wherequery = "(" . $wherequery . ")" . " OR is_universal_profile = 1 ";
            }

            $query .= $wherequery;
            if ($offset == null)
                $query .= " HAVING(test_rto IS NULL) LIMIT 10";
            else if ($offset != null)
                $query .= " HAVING(test_rto IS NULL) LIMIT $offset,10";
            else
                $query .= " HAVING(test_rto IS NULL) LIMIT 10";
            $result['result'] = $this->db->query($query)->result_array();
            /* $result['query'] = $query; */
            return $result;
        }
        return false;
    }

}

?>