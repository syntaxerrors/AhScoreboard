<?php

class VideoObserver {

	public function deleted($model)
	{
		if ($model->child != null) {
			$model->child->parentId = null;
			$model->child->save();
		}

		if ($model->types->count() > 0) {
			$model->types->delete();
		}

		if ($model->games->count() > 0) {
			$model->games->delete();
		}

		if ($model->actors->count() > 0) {
			$model->actors->delete();
		}

		if ($model->winners->count() > 0) {
			$model->winners->delete();
		}

		if ($model->quotes->count() > 0) {
			$model->quotes->delete();
		}

		if ($model->rounds->count() > 0) {
			$model->rounds->delete();
		}
	}
}