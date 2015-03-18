<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
	<div class="col-md-12">
		<table class="table table-condensed">
			<caption></caption>
			<thead>
				<tr>
					<th>Date</th>
					<th>Forum</th>
					<th>Topic</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($topics as $topic) : ?>
					<tr>
						<td><?= $topic->date ?></td>
						<td><a href="<?= $topic->forum->permalink ?>"><?= $topic->forum->title ?></a></td>
						<td><a href="<?= $topic->permalink ?>"><?= $topic->title ?></a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
	</div>
</div><!-- .row -->