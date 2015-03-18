<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
	<div class="col-md-12">
		<table class="table table-condensed">
			<caption></caption>
			<thead>
				<tr>
					<th>Date</th>
					<th>Topic</th>
					<th>Post</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($posts as $post) : ?>
					<tr>
						<td><?= $post->date ?></td>
						<td><a href="<?= $post->topic->permalink ?>"><?= $post->topic->title ?></a></td>
						<td><a href="<?= $post->permalink ?>">#<?= $post->id ?></a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
	</div>
</div><!-- .row -->