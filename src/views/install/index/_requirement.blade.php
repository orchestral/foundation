<div class="row">
	<div class="twelve columns rounded box">
		<h3>{{ trans('orchestra/foundation::install.system.title') }}</h3>

		<p>{{ trans('orchestra/foundation::install.system.description') }}</p>

		<table class="table table-bordered table-striped requirements">
			<thead>
				<tr>
					<th>{{ trans('orchestra/foundation::install.system.requirement') }}</th>
					<th>{{ trans('orchestra/foundation::install.system.status') }}</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($checklist as $name => $requirement)
				<tr>
					<td>
						{{ trans("orchestra/foundation::install.system.{$name}.name", $requirement['data']) }}
						@if (! ($requirement['is'] === $requirement['should']))
						<div class="alert{{ true === $requirement['explicit'] ? ' alert-error ' : '' }}">
							<strong>{{ trans("orchestra/foundation::install.solution") }}:</strong>
							{{ trans("orchestra/foundation::install.system.{$name}.solution", $requirement['data']) }}
						</div>
						@endif
					</td>
					<td>
						@if ($requirement['is'] === $requirement['should'])
							<button class="btn btn-success btn-block disabled">
								{{ trans('orchestra/foundation::install.status.work') }}
							</button>
						@else
							@if (true === $requirement['explicit'])
								<button class="btn btn-danger btn-block disabled">
									{{ trans('orchestra/foundation::install.status.not') }}
								</button>
							@else
								<button class="btn btn-warning btn-block disabled">
									{{ trans('orchestra/foundation::install.status.still') }}
								</button>
							@endif
						@endif
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
