<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1>Forums</h1>
			</div>
		</div>
		
		<div class="col-md-12">
			<table class="table table-striped table-condensed table-hover">
				<caption></caption>
				<thead>
					<tr>
						<th>Forums</th>
						<th>Topics</th>
						<th>Posts</th>
						<th class="hidden-xs">Latest topic</th>
					</tr>
				</thead>
				<tbody>
					<?php if ($forums) : ?>
						<?php foreach ($forums as $forum) : ?>
							<tr>
								<td>
									<p>
										<a href="<?= $forum->slug ?>"><?= $forum->title ?></a><br>
										<small><?= $forum->description ?></small>
									</p>
								</td>
								<td>
									<p>
										<small></small>
									</p>
								</td>
								<td>
									<p>
										<small></small>
									</p>
								</td>
								<td class="hidden-xs">
									<p>
										<small></small></p>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
			
		</div>
		
	</div><!-- .row -->
</div><!-- .container -->

<?php var_dump($forums); ?>