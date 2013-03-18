<?php
/**
 * DNProject represents a project that relates to a group of target
 * environments, and a has access to specific build tarballs.
 *
 * For the project to be able to pick up builds, the tarballs need to 
 * be stored in similarly named directories, e.g.:
 * deploynaut-resources/envs/ss3/dev.rb
 * deploynaut-resources/builds/ss3/ss3-1.0.3.tar.gz
 */

class DNProject extends DataObject {
	static $db = array(
		"Name" => "Varchar",
	);
	static $has_many = array(
		"Environments" => "DNEnvironment",
	);
	static $many_many = array(
		"Viewers" => "Group",
	);

	static $summary_fields = array(
		"Name",
		"ViewersList",
	);
	static $searchable_fields = array(
		"Name",
	);

	static function get($callerClass = null, $filter = "", $sort = "", $join = "", $limit = null,
			$containerClass = 'DataList') {
		return new DNProjectList('DNProject');
	}

	static function create_from_path($path) {
		$p = new DNProject;
		$p->Name = $path;
		$p->write();
		return $p;
	}

	public function canView($member = null) {
		if(!$member) $member = Member::currentUser();

		if(Permission::checkMember($member, "ADMIN")) return true;

		foreach($this->Viewers() as $group) {
			if($group->Members()->byID($member->ID)) return true;
		}
		return false;
	}

	function getViewersList() {
		return implode(", ", $this->Viewers()->column("Title"));
	}

	function DNData() {
		return Injector::inst()->get('DNData');
	}

	/**
	 * Provides a DNBuildList of builds found in this project.
	 */
	function DNBuildList() {
		return new DNBuildList($this->DNData()->getBuildDir().'/'.$this->Name, $this, $this->DNData());
	}

	/**
	 * Provides a DNEnvironmentList of environments found in this project.
	 */
	public function DNEnvironmentList() {
		return DNEnvironment::get()->filter('ProjectID', $this->ID)->setProjectID($this->ID);
	}


	public function Link() {
		return "naut/project/$this->Name";
	}

	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$environments = $fields->dataFieldByName("Environments");

		$fields->fieldByName("Root")->removeByName("Viewers");
		$fields->fieldByName("Root")->removeByName("Environments");

		$fields->addFieldToTab("Root.Main", $environments);
		$fields->addFieldToTab("Root.Main",
			new CheckboxSetField("Viewers", "Groups with read access to this project",
				Group::get()->map()));


		return $fields;
	}
}
