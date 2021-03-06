<?php

class MenuController extends Core_BaseController
{

	public function getMenu()
	{
		Menu::handler('main')
			->add('/', 'Home')
			->add('/actors', 'Actors')
			->add('/quotes', 'Quotes')
			->add('/memberlist', 'Memberlist')
			->add('/about', 'About');

		if (Auth::check()) {
			// Manage Menu
			if ($this->hasPermission('DEVELOPER')) {
				Menu::handler('mainRight')
					->add('javascript:void(0);', 'Management', Menu::items()
						->add('/admin', 'Dev Panel')
						->add('/manage', 'Video Panel')
						->add('/video/add', 'Add Video')
						->add('/video/rss', 'RSS'));
			}

			// User Menu
			Menu::handler('mainRight')
				->add('/user/view/'. $this->activeUser->id, $this->activeUser->username, Menu::items()
					->add('/messages', 'My Messages... ('. $this->activeUser->unreadMessageCount .')')
					->add('/user/favorites', 'Favorites')
					->add('/user/unwatched', 'Unwatched Videos')
					->add('/user/account', 'Edit Profile')
					->add('/logout', 'Logout'));
		} else {
			Menu::handler('mainRight')
				->add('/login', 'Login')
				->add('/register', 'Register')
				->add('/forgotPassword', 'Forgot Password');
		}
	}

	public function setAreaDetails($area)
	{
		$location = (Request::segment(2) != null ? ': '. ucwords(Request::segment(2)) : '');

		if ($area != null) {
			$this->pageTitle = ucwords($area).$location;
		} else {
			$this->pageTitle = Config::get('core::siteName'). (Request::segment(1) != null ? ': '.ucwords(Request::segment(1)) : '');
		}
	}
}