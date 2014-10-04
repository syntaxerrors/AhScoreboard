{{ bForm::setType('basic')->open(false, array('id' => 'quoteForm')) }}
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					{{ $video->title }} Quotes
					<div class="panel-btn">
						{{ HTML::linkIcon('http://youtube.com/watch?v='. $video->link, 'fa fa-youtube') }}
					</div>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-4">
							{{ bForm::text('title', null, array('placeholder' => 'Title'), 'Title') }}
						</div>
						<div class="col-md-4">
							{{ bForm::text('timeStart', null, array('placeholder' => 'Start Time'), 'Start Time') }}
						</div>
						<div class="col-md-4">
							{{ bForm::text('timeEnd', null, array('placeholder' => 'End Time'), 'End Time') }}
						</div>
					</div>
					<div class="row">
						<div class="col-md-8">
							{{ bForm::textarea('quotes', null, array('placeholder' => 'Quotes'), 'Quotes') }}
						</div>
						@if ($video->series->checkType('CHARACTERS'))
							<div class="col-md-4">
								{{ bForm::select('characters[]', $video->characters->toSelectArray(false), null, array('multiple', 'style' => 'height: 190px;'), 'Actors') }}
							</div>
						@else
							<div class="col-md-4">
								{{ bForm::select('actors[]', $video->actors->where('morph_type', 'Actor')->morph->toSelectArray(false), null, array('multiple', 'style' => 'height: 190px;'), 'Actors') }}
							</div>
						@endif
					</div>
					<div class="row">
						<div class="col-md-12">
							{{ bForm::jsonSubmit('Add Quote') }}
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<div id="message"></div>
				</div>
			</div>
		</div>
	</div>
{{ bForm::close() }}
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Existing Quotes</div>
			<table class="table table-condensed table-hover table-striped" id="existingQuotes">
				<thead>
					<tr>
						<th>Title</th>
						<th>Start Time</th>
						<th>End Time</th>
						<th>Members</th>
					</tr>
				</thead>
				<tbody>
					@if ($video->quotes->count() > 0)
						@foreach ($video->quotes as $quote)
							<tr>
								<td>{{ $quote->linkOnly }}</td>
								<td>{{ $quote->timeStart }}</td>
								<td>{{ $quote->timeEnd }}</td>
								<td>{{ implode(', ', $quote->actors->morph->firstNameLink->toArray()) }}</td>
							</tr>
						@endforeach
					@else
						<tr class="placeholder">
							<td colspan="4">No quotes for this video.</td>
						</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
</div>