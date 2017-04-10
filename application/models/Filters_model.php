<?php

class Filters_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /* This function will fetch main filters information based on column and value provided. */

    function getMainFilterByCol($column, $value) {
        $this->db->select("*");
        $this->db->from("main_filters");
        $this->db->join("sub_filters", "sub_filters.filter_id = main_filters.filter_id");
        $this->db->where("main_filters.$column", $value);
        return $this->db->get()->result_array();
    }

    /* This function will fetch all main filters information. */

    function getAllMainFilters() {
        $this->db->select("*");
        $this->db->from("main_filters");
        $this->db->join("sub_filters", "sub_filters.filter_id = main_filters.filter_id");
        return $this->db->get()->result_array();
    }

    /* This function will add main filter information or update existing filter if id will be provided along with $data. */

    function manageMainFilter($data) {
        if (!empty($data['filter_id'])) {
            $data['updated_date'] = date("Y-m-d H:i:s");
            $this->db->where('filter_id', $data['filter_id']);
            $this->db->update('main_filters', $data);
            if ($this->db->affected_rows() >= 1) {
                return true;
            } else
                return false;
        } else {
            $this->db->insert('main_filters', $data);
        }
    }

    /* This function will fetch sub filter information based on column and value passed as parameter. */

    function getSubFilterByCol($column, $value) {
        $this->db->select("*");
        $this->db->from("sub_filters");
        $this->db->where($column, $value);
        return $this->db->get()->result_array();
    }

    /* This function will fetch user's sub filter information based on column and value passed as parameter. */

    function getUserSubFilterByCol($column, $value) {
        $this->db->select("user_filter.*,sub_filters.sub_filter_name");
        $this->db->from("user_filter");
        $this->db->join("sub_filters", "sub_filters.sub_filter_id = user_filter.sub_filter_id");
        $this->db->where($column, $value);
        return $this->db->get()->result_array();
    }

    /* This function will delete user's sub filter information based on values provided. */

    function deleteUserSubFiltersByCol($col, $value, $array = false) {
        if ($array != false && is_array($array)) {
            $this->db->where_in('sub_filter_id', $array);
            $this->db->delete('user_filter', array($col => $value));
        } else {
            $this->db->delete('user_filter', array($col => $value));
        }
        return true;
    }

    /* This function will fetch data of next filter based on the previous filter id passed. */

    function getNextFilterInfo($filter_id) {
        if (!empty($filter_id) && is_numeric($filter_id)) {
            $this->db->select("*");
            $this->db->from("main_filters");
            $this->db->where('filter_id > ', $filter_id);
            $this->db->limit(1);
            return $this->db->get()->row_array();
        }
        return false;
    }

}

?>