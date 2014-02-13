<?php

class RoundObserver {

	public function deleted($model)
	{
		if ($model->actors->count() > 0) {
			$model->actors->delete();
		}

		if ($model->winners->count() > 0) {
			$model->winners->delete();
		}

		if ($model->game != null) {
			$model->game->delete();
		}
	}
}