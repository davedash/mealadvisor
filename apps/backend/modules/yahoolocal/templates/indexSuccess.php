<?php echo form_tag('yahoolocal') ?>
<fieldset>
	<ol>
		<li>
			<label for="query">Search</label>
			<?php echo input_tag('query',$sf_request->getParameter('query', $query)) ?>
		</li>
		<li>
			<label for="sort">Sort</label>
			<?php echo select_tag('sort', options_for_select(array('title'=>'title','dist'=>'dist')));?>
		</li>
		<li>
			<label for="location">Location</label>
			<?php echo input_tag('location',$sf_request->getParameter('location', $location)) ?>
		</li>
		<li>
			<label for="radius">Radius</label>
			<?php echo input_tag('radius',$sf_request->getParameter('radius', $radius)) ?>
		</li>


	</ol>
</fieldset>
<p><?php echo submit_tag('find') ?></p>
</form>

<?php if (!empty($results)): ?>
	<p>
		Showing results <?php echo $local->getFirst() ?> through 
		<?php echo $local->getLast() ?> of <?php echo $local->getTotal() ?>.
	</p>
	<p><?php echo $local->query_url ?></p>
	
	<p>Page: 
		<?php if ($local->getPage() > 1): ?>
		<?php echo link_to('&lt;', $route . $local->getPage()-1) ?>  
		<?php endif ?>

		<?php $totalPages = $local->getLastPage();
		$cur_page = $local->getPage();
		for($i = 1; $i <= $totalPages; $i++): ?>
		<?php echo ($i == $cur_page) ? $i : link_to($i, $route . $i) ?>  
		<?php endfor ?>
		<?php if ($local->getPage() != $local->getLastPage()): ?>
		<?php echo link_to('&gt;', $route . $local->getPage()+1) ?>  
		
		<?php endif ?>
	</p>
		
	
	<table>
		<thead>
			<tr>
				<th>Title</th><th>Address</th><th>Phone</th><th>URL</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($results as $result): ?>
    	<tr>
				<td><?php echo $result['Title'] ?></td>
				
				<td>
					<?php echo $result['Address'] ?>
					<br/>
					<?php echo $result['City'] ?>,
					<?php echo $result['State'] ?>
				</td>
				
				<td><?php echo $result['Phone'] ?></td>
				
				<td><?php echo $result['BusinessUrl'] ?></td>
				<td>
					<?php echo form_tag('yahoolocal/add') ?>
						<?php echo input_hidden_tag('title', $result['Title']) ?>
						<?php echo input_hidden_tag('address', $result['Address']) ?>
						<?php echo input_hidden_tag('city', $result['City']) ?>
						<?php echo input_hidden_tag('state', $result['State']) ?>
						<?php echo input_hidden_tag('latitude', $result['Latitude']) ?>
						<?php echo input_hidden_tag('longitude', $result['Longitude']) ?>
						<?php echo input_hidden_tag('phone', $result['Phone']) ?>
						<?php echo input_hidden_tag('url', $result['BusinessUrl']) ?>
						<?php echo submit_tag('add?') ?>
					</form>
				</td>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>
<?php endif ?>
