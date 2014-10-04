@if ($video->characters->count() > 0)
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">Characters</div>
				<table class="table table-condensed">
					<tbody>
						@foreach ($video->characters as $character)
							<tr>
								<td>{{ $character->actor->link }} as {{ $character->name }}</td>
								<td class="text-right">
									<div class="btn-group">
										<a href="/manage/delete-character/{{ $video->id }}/{{ $character->id }}" class="confirm-remove btn btn-xs btn-danger"><i class="fa fa-trash-o"></i></a>
									</div>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endif