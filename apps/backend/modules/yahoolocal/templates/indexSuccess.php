<?php use_helper('Object');?>
<?php echo form_tag('yahoolocal') ?>
<fieldset>
	<ol>
		<li>
			<label for="query">Search</label>
			<?php echo input_tag('query',$sf_request->getParameter('query', $query)) ?>
		</li>
		<li>
			<label for="category">Category</label>
			<?php echo select_tag('category', objects_for_select(YahooLocalCategoryPeer::retrieveAll(),'getYid','getDescription', $category)) ?>
		</li>
		<li>
			<label for="sort">Sort</label>
			<?php echo select_tag('sort', options_for_select(array('title'=>'title','distance'=>'distance'),$sf_request->getParameter('sort', $sort)));?>
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
	<p><?php echo link_to($local->query_url,$local->query_url) ?></p>
	
	<p>Page: 
		<?php if ($local->getPage() > 1): ?>
		<?php echo link_to('&lt;', $route . ($local->getPage()-1)) ?>  
		<?php endif ?>

		<?php $totalPages = $local->getLastPage();
		$cur_page = $local->getPage();
		for($i = 1; $i <= $totalPages; $i++): ?>
		<?php echo ($i == $cur_page) ? $i : link_to($i, $route . $i) ?>  
		<?php endfor ?>
		<?php if ($local->getPage() != $local->getLastPage()): ?>
		<?php echo link_to('&gt;', $route . ($local->getPage()+1)) ?>  
		
		<?php endif ?>
	</p>
	
	<?php echo form_tag('yahoolocal/addAll') ?>
	
	<table>
		<thead>
			<tr>
				<th>Distance</th>
				<th>Title</th><th>Address</th><th>Phone</th><th>URL</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($results as $result): ?>
    	<tr>
				<td><?php echo $result->Distance ?></td>
				<td><?php echo YahooLocal::sanitizeText($result->Title) ?></td>
				
				<td>
					<?php echo $result->Address ?>
					<br/>
					<?php echo $result->City ?>,
					<?php echo $result->State ?>
				</td>
				
				<td><?php echo $result->Phone ?></td>
				
				<td>
					<?php if ($url = (string) $result->BusinessUrl): ?>
					<?php echo link_to($url,$url) ?>
					<?php endif ?>				
				</td>
				<td>
					<?php if ($location = LocationPeer::retrieveByDataSourceKey(Location::YAHOO_LOCAL, $result['id'])): ?>
					  Matches: <?php echo $location->getRestaurant() ?>: <?php echo $location ?>
					<?php else:?>
					<?php echo link_to('add', 'yahoolocal/add?yid='.$result['id']) ?>
					<?php echo checkbox_tag('yid[]',$result['id'], true) ?>
					<?php endif ?>
					
				</td>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>
	<?php echo submit_tag('add all checked (dbl check for chains)') ?>
	</form>
<?php endif ?>
