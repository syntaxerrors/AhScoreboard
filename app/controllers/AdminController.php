<?php

class AdminController extends Core_AdminController {

	public function getIndex()
	{
		// Get the collapse values
		if (!Session::has('COLLAPSE_ADMIN_PERMISSIONS')) {
			Session::put('COLLAPSE_ADMIN_PERMISSIONS', $this->activeUser->getPreferenceValueByKeyName('COLLAPSE_ADMIN_PERMISSIONS'));
		}
		if (!Session::has('COLLAPSE_ADMIN_GENERAL')) {
			Session::put('COLLAPSE_ADMIN_GENERAL', $this->activeUser->getPreferenceValueByKeyName('COLLAPSE_ADMIN_GENERAL'));
		}
		if (!Session::has('COLLAPSE_ADMIN_TYPES')) {
			Session::put('COLLAPSE_ADMIN_TYPES', $this->activeUser->getPreferenceValueByKeyName('COLLAPSE_ADMIN_TYPES'));
		}
		if (!Session::has('COLLAPSE_ADMIN_AH')) {
			Session::put('COLLAPSE_ADMIN_AH', $this->activeUser->getPreferenceValueByKeyName('COLLAPSE_ADMIN_AH'));
		}

		LeftTab::
			addPanel()
				->setId('ADMIN_PERMISSIONS')
				->setTitle('Permissions')
				->setBasePath('admin')
				->addTab('Users',           'users')
				->addTab('Role Users',      'role-users')
				->addTab('Roles',           'roles')
				->addTab('Action Roles',    'action-roles')
				->addTab('Actions',         'actions')
				->buildPanel()
			->addPanel()
				->setId('ADMIN_GENERAL')
				->setTitle('General')
				->setBasePath('admin')
				->addTab('User Preferences',    'user-preferences')
				->addTab('Theme',               'theme')
				->addTab('Migrations',          'migrations')
				->addTab('Seeds',               'seeds')
				->addTab('App Configs',         'app-configs')
				->addTab('SQL Tables',          'sql-tables')
				->buildPanel()
			->addPanel()
				->setId('ADMIN_AH')
				->setTitle('AH Details')
				->setBasePath('admin')
				->addTab('Series',           'series')
				->addTab('Games',            'games')
				->addTab('Teams',            'teams')
				->addTab('Team Actors',      'teamactors')
				->addTab('Actors',           'actors')
				->buildPanel()
			->addPanel()
				->setId('ADMIN_TYPES')
				->setTitle('Class Types')
				->setBasePath('admin')
				->addTab('Types',           'types')
				->addTab('Message',         'message')
				->addTab('Forum Category',  'forum-category')
				->addTab('Forum Board',     'forum-board')
				->addTab('Forum Reply',     'forum-reply')
				->buildPanel()
			->setCollapsable(true)
		->make();
	}

	public function getSeries()
	{
		$series = Series::orderByNameAsc()->get();

		// Set up the one page crud main details
		Crud::setTitle('Series')
				 ->setSortProperty('name')
				 ->setDeleteLink('/admin/seriesdelete/')
				 ->setDeleteProperty('id')
				 ->setResources($series);

		// Add the display columns
		Crud::addDisplayField('name')
				 ->addDisplayField('keyName');

		// Add the form fields
		Crud::addFormField('name', 'text')
				 ->addFormField('keyName', 'text');

		// Set the view data
		Crud::make();
	}

	public function postSeries()
	{
		$this->skipView();

		// Set the input data
		$input = e_array(Input::all());

		if ($input != null) {
			// Get the object
			$series              = (isset($input['id']) && strlen($input['id']) == 10 ? Series::find($input['id']) : new Series);
			$series->name        = $input['name'];
			$series->keyName     = $input['keyName'];

			// Attempt to save the object
			$this->save($series);

			// Handle errors
			if ($this->errorCount() > 0) {
				Ajax::addErrors($this->getErrors());
			} else {
				Ajax::setStatus('success')->addData('resource', $series->toArray());
			}

			// Send the response
			return Ajax::sendResponse();
		}
	}

	public function getSeriesdelete($seriesId)
	{
		$this->skipView();

		$series = Series::find($seriesId)->delete();

		return Redirect::to('/admin#series');
	}

	public function getGames()
	{
		$games = Game::orderByNameAsc()->paginate(20);

		// Set up the one page crud main details
		Crud::setTitle('Games')
				 ->setSortProperty('name')
				 ->setDeleteLink('/admin/gamedelete/')
				 ->setDeleteProperty('id')
				 ->setPaginationFlag(true)
				 ->setResources($games);

		// Add the display columns
		Crud::addDisplayField('name')
				 ->addDisplayField('keyName');

		// Add the form fields
		Crud::addFormField('name', 'text')
				 ->addFormField('keyName', 'text');

		// Set the view data
		Crud::make();
	}

	public function postGames()
	{
		$this->skipView();

		// Set the input data
		$input = e_array(Input::all());

		if ($input != null) {
			// Get the object
			$game                = (isset($input['id']) && strlen($input['id']) == 10 ? Game::find($input['id']) : new Game);
			$game->name          = $input['name'];
			$game->keyName       = $input['keyName'];

			// Attempt to save the object
			$this->save($game);

			// Handle errors
			if ($this->errorCount() > 0) {
				Ajax::addErrors($this->getErrors());
			} else {
				Ajax::setStatus('success')->addData('resource', $game->toArray());
			}

			// Send the response
			return Ajax::sendResponse();
		}
	}

	public function getGamedelete($gameId)
	{
		$this->skipView();

		$game = Game::find($gameId)->delete();

		return Redirect::to('/admin#games');
	}

	public function getTeams()
	{
		$teams = Team::orderByNameAsc()->paginate(20);

		// Set up the one page crud main details
		Crud::setTitle('Teams')
				 ->setSortProperty('name')
				 ->setDeleteLink('/admin/teamdelete/')
				 ->setDeleteProperty('id')
				 ->setPaginationFlag(true)
				 ->setResources($teams);

		// Add the display columns
		Crud::addDisplayField('name', '/teams/view/', 'id')
				 ->addDisplayField('keyName');

		// Add the form fields
		Crud::addFormField('name', 'text')
				 ->addFormField('keyName', 'text')
				 ->addFormField('firstMentionLink', 'text')
				 ->addFormField('firstMentionTitle', 'text');

		// Set the view data
		Crud::make();
	}

	public function postTeams()
	{
		$this->skipView();

		// Set the input data
		$input = e_array(Input::all());

		if ($input != null) {
			// Get the object
			$team                    = (isset($input['id']) && strlen($input['id']) == 10 ? Team::find($input['id']) : new Team);
			$team->name              = $input['name'];
			$team->keyName           = $input['keyName'];
			$team->firstMentionLink  = $input['firstMentionLink'];
			$team->firstMentionTitle = $input['firstMentionTitle'];

			// Attempt to save the object
			$this->save($team);

			// Handle errors
			if ($this->errorCount() > 0) {
				Ajax::addErrors($this->getErrors());
			} else {
				Ajax::setStatus('success')->addData('resource', $team->toArray());
			}

			// Send the response
			return Ajax::sendResponse();
		}
	}

	public function getTeamdelete($teamId)
	{
		$this->skipView();

		$team = Team::find($teamId)->delete();

		return Redirect::to('/admin#teams');
	}

	public function getActors()
	{
		$actors = Actor::orderByNameAsc()->paginate(20);

		// Set up the one page crud main details
		Crud::setTitle('Actors')
				 ->setSortProperty('firstName')
				 ->setDeleteLink('/admin/actordelete/')
				 ->setDeleteProperty('id')
				 ->setPaginationFlag(true)
				 ->setResources($actors);

		// Add the display columns
		Crud::addDisplayField('fullName', '/actors/view/', 'id')
				 ->addDisplayField('keyName')
				 ->addDisplayField('twitterHandle');

		// Add the form fields
		Crud::addFormField('firstName', 'text')
				 ->addFormField('lastName', 'text')
				 ->addFormField('keyName', 'text')
				 ->addFormField('bio', 'textarea')
				 ->addFormField('twitterHandle', 'text')
				 ->addFormField('rtLink', 'text', null, false, 'RoosterTeeth.com Link')
				 ->addFormField('rtWikiLink', 'text', null, false, 'RoosterTeeth Wiki Link');

		// Set the view data
		Crud::make();
	}

	public function postActors()
	{
		$this->skipView();

		// Set the input data
		$input = e_array(Input::all());

		if ($input != null) {
			// Get the object
			$actor                = (isset($input['id']) && strlen($input['id']) == 10 ? Actor::find($input['id']) : new Actor);
			$actor->firstName     = $input['firstName'];
			$actor->lastName      = $input['lastName'];
			$actor->keyName       = $input['keyName'];
			$actor->bio           = $input['bio'];
			$actor->twitterHandle = $input['twitterHandle'];
			$actor->rtLink        = $input['rtLink'];
			$actor->rtWikiLink    = $input['rtWikiLink'];

			// Attempt to save the object
			$this->save($actor);

			// Handle errors
			if ($this->errorCount() > 0) {
				Ajax::addErrors($this->getErrors());
			} else {
				Ajax::setStatus('success')->addData('resource', $actor->toArray());
			}

			// Send the response
			return Ajax::sendResponse();
		}
	}

	public function getActordelete($actorId)
	{
		$this->skipView();

		$actor = Actor::find($actorId)->delete();

		return Redirect::to('/admin#actors');
	}

	public function getTeamactors()
	{
		$teams        = Team::orderByNameAsc()->paginate(10);
		$actors       = Actor::orderByNameAsc()->get();
		$teamsArray   = $this->arrayToSelect($teams, 'id', 'name', 'Select a team');
		$actorsArray  = $this->arrayToSelect($actors, 'id', 'fullName', 'None');

		// Set up the one page crud
		Crud::setTitle('Team Membership')
				 ->setSortProperty('name')
				 ->setPaginationFlag(true)
				 ->setDeleteFlag(false)
				 ->setMulti($teams, 'actors')
				 ->setMultiColumns(array('Team', 'Actors'))
				 ->setMultiDetails(array('name' => 'name', 'field' => 'team_id'))
				 ->setMultiPropertyDetails(array('name' => 'firstName', 'field' => 'actor_id'));

		// Add the form fields
		Crud::addFormField('team_id', 'select', $teamsArray)
				 ->addFormField('actor_id', 'multiselect', $actorsArray);

		Crud::make();
	}

	public function postTeamactors()
	{
		$this->skipView();

		// Set the input data
		$input = e_array(Input::all());

		if ($input != null) {
			// Remove all existing roles
			$teamMembers = Team_Actor::where('team_id', $input['team_id'])->get();

			if ($teamMembers->count() > 0) {
				foreach ($teamMembers as $teamMember) {
					$teamMember->delete();
				}
			}

			// Add any new roles
			if (count($input['actor_id']) > 0) {
				foreach ($input['actor_id'] as $actor_id) {
					if ($actor_id == '0') continue;

					$teamMember            = new Team_Actor;
					$teamMember->team_id   = $input['team_id'];
					$teamMember->actor_id  = $actor_id;

					$this->save($teamMember);
				}
			}

			// Handle errors
			if ($this->errorCount() > 0) {
				Ajax::addErrors($this->getErrors());
			} else {
				$team = Team::find($input['team_id']);

				$main = $team->toArray();
				$main['multi'] = $team->actors->id->toJson();

				Ajax::setStatus('success')
									->addData('resource', $team->actors->toArray())
									->addData('main', $main);
			}

			// Send the response
			return Ajax::sendResponse();
		}
	}

	public function getTypes()
	{
		$types = Type::orderByNameAsc()->get();

		// Set up the one page crud main details
		Crud::setTitle('Types')
				 ->setSortProperty('name')
				 ->setDeleteLink('/admin/typedelete/')
				 ->setDeleteProperty('id')
				 ->setResources($types);

		// Add the display columns
		Crud::addDisplayField('name')
				 ->addDisplayField('keyName');

		// Add the form fields
		Crud::addFormField('name', 'text')
				 ->addFormField('keyName', 'text')
				 ->addFormField('videoFlag', 'select', array('Not video only', 'Video only'));

		// Set the view data
		Crud::make();
	}

	public function postTypes()
	{
		$this->skipView();

		// Set the input data
		$input = e_array(Input::all());

		if ($input != null) {
			// Get the object
			$type            = (isset($input['id']) && $input['id'] != null ? Type::find($input['id']) : new Type);
			$type->name      = $input['name'];
			$type->keyName   = $input['keyName'];
			$type->videoFlag = $input['videoFlag'];

			// Attempt to save the object
			$this->save($type);

			// Handle errors
			if ($this->errorCount() > 0) {
				Ajax::addErrors($this->getErrors());
			} else {
				Ajax::setStatus('success')->addData('resource', $type->toArray());
			}

			// Send the response
			return Ajax::sendResponse();
		}
	}

	public function getTypedelete($typeId)
	{
		$this->skipView();

		$type = Type::find($typeId)->delete();

		return Redirect::to('/admin#types');
	}
}