<?php 

class User extends Syntax\Core\User {

	public function __construct()
	{
		parent::__construct();

		self::$relationsData = array_merge(parent::$relationsData, array(
			'favorites' => array('hasMany', 'User_Favorite',	'foreignKey' => 'user_id'),
			'watched'   => array('hasMany', 'User_Watch',		'foreignKey' => 'user_id'),
		));
	}

	public function isFavorite($favoriteId)
	{
		$favorites = $this->favorites->filter(function ($favorite) use ($favoriteId) {
			if ($favorite->morph_id == $favoriteId) return true;
		});
		return $favorites->count() > 0;
	}

	public function hasWatched($videoId)
	{
		$watched = $this->watched->filter(function ($watch) use ($videoId) {
			if ($watch->video_id == $videoId) return true;
		});

		return $watched->count() > 0;
	}

	public function setWatched($videoId)
	{
		$watched = User_Watch::where('user_id', Auth::user()->id)->where('video_id', $videoId)->first();

		if (is_null($watched)) {
			$watched           = new User_Watch;
			$watched->user_id  = Auth::user()->id;
			$watched->video_id = $videoId;

			$watched->save();
		}
	}

	public function removeWatched($videoId)
	{
		$watched = User_Watch::where('user_id', Auth::user()->id)->where('video_id', $videoId)->first();

		if (!is_null($watched)) {
			$watched->delete();
		}
	}

	public function setFavorite($id, $type)
	{
		$favorite = User_Favorite::where('user_id', Auth::user()->id)->where('morph_id', $id)->where('morph_type', $type)->first();

		if (is_null($favorite)) {
			$favorite             = new User_Favorite;
			$favorite->user_id    = Auth::user()->id;
			$favorite->morph_id   = $id;
			$favorite->morph_type = $type;

			$favorite->save();
		}
	}

	public function removeFavorite($id, $type)
	{
		$favorite = User_Favorite::where('user_id', Auth::user()->id)->where('morph_id', $id)->where('morph_type', $type)->first();

		if (!is_null($favorite)) {
			$favorite->delete();
		}
	}
}