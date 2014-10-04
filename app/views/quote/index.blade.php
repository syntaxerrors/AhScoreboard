<div class="panel panel-default">
	<div class="panel-heading">Quotes</div>
	<table class="table table-condensed">
		<thead>
			<tr>
				<th style="width: 20%;">Video Name</th>
				<th style="width: 200px;">Quote Name</th>
				<th style="width: 200px;">Start Time</th>
				<th>Actors in the quote</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($videos as $video)
				<tr>
					<td>{{ $video->linkTo }}</td>
					<td colspan="3">
						<table class="table table-condensed table-hover">
							@foreach ($video->quotes as $quote)
								<tr>
									<td style="width: 200px;">{{ $quote->linkOnly }}</td>
									<td style="width: 200px;">{{ $quote->timeStart }}</td>
									<td>{{ implode(', ', $quote->actors->morph->firstNameLink->toArray()) }}</td>
								</tr>
							@endforeach
						</table>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	<div class="panel-footer">
		<div class="text-center">
			{{ $videos->links() }}
		</div>
	</div>
</div>