<?php
function get_usergroups() 
{
	

	$model = registry()->get('loader')->model('usergroup');

	return $model->getUsergroups();
}

function get_usergroup_user_count($group_id) 
{
	

	$model = registry()->get('loader')->model('usergroup');

	return $model->totalUser($group_id);
}

function get_the_usergroup($id, $field = null) 
{
	

	$model = registry()->get('loader')->model('usergroup');

	$usergroup = $model->getUsergroup($id);

	if ($field && isset($usergroup[$field])) {
		return $usergroup[$field];
	} elseif ($field) {
		return;
	}

	return $usergroup;
}