<?php

class Filters_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function getMainFilterByCol($column, $value) {
        $this->db->select("*");
        $this->db->from("main_filters");
        $this->db->join("sub_filters", "sub_filters.filter_id = main_filters.filter_id");
        $this->db->where("main_filters.$column", $value);
        return $this->db->get()->result_array();
    }

    function getAllMainFilters() {
        $this->db->select("*");
        $this->db->from("main_filters");
        $this->db->join("sub_filters", "sub_filters.filter_id = main_filters.filter_id");
        return $this->db->get()->result_array();
    }

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

    function getSubFilterByCol($column, $value) {
        $this->db->select("*");
        $this->db->from("sub_filters");
        $this->db->where($column, $value);
        return $this->db->get()->result_array();
    }

    function getUserSubFilterByCol($column, $value) {
        $this->db->select("*");
        $this->db->from("user_filter");
        $this->db->where($column, $value);
        return $this->db->get()->result_array();
    }

    function deleteUserSubFiltersByCol($col, $value, $array = false) {
        if ($array != false && is_array($array)) {
            $this->db->where_in('sub_filter_id', $array);
            $this->db->delete('user_filter', array($col => $value));
        } else {
            $this->db->delete('user_filter', array($col => $value));
        }
        return true;
    }

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