<?php

// Functions

function find($table, $id, $text) {
	global $mysqli;
	
	$stmt_up = $mysqli->prepare("SELECT * FROM {$table} WHERE id = ?");
	$stmt_up->bind_param("s", $id);
	$stmt_up->execute();
	$r = $stmt_up->get_result();
	if ($r->num_rows) {
		return $r->fetch_assoc();
	}

	$ret = array();
    $ret['result'] = "ERR";
    $ret['error'] = $text;
	echo json_encode($ret);
	exit();
}

function isValidJSON($str) {
	json_decode($str);
	return json_last_error() == JSON_ERROR_NONE;
}
