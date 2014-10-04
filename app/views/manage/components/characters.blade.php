{{ bForm::setType('basic')->open(false, array('url' => '/manage/add-characters/'. $video->id, 'id' => 'winnersForm')) }}
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">{{ $video->title }} Characters</div>
				<div class="panel-body">
					<div id="winners">
						<div class="row" id="template">
							<div class="col-md-12">
								{{ bForm::select('character_id', $characters, null, array(), 'Character') }}
							</div>
						</div>
					</div>
					<div class="btn-group">
						{{ Form::submit(
							'Add Character',
							array(
								'class' => 'btn btn-sm btn-primary',
							)
						) }}
					</div>
				</div>
				<div class="panel-footer">
					<div id="message"></div>
				</div>
			</div>
			@include('manage.components.videocharacters')
			@include('manage.components.videodetails')
		</div>
	</div>
{{ bForm::close() }}