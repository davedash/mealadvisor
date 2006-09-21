<?php use_helper('Global','Text');?>
<Results>
	<AuthorInfo description="Find restaurants" author="reviewsby.us/Dave Dash"/>
	<ResultSpec id="restaurant">
		<Query>[Restaurant]</Query>
		<Response>

			<Output name="title">[0.name]</Output>
			<Output name="text1">[0.description]</Output>
			<Output name="text1">Rating: [0.rating] out of 5 stars</Output>
			
			<Output name="more_url">reviewsby.us/restaurant/[0.stripped]</Output>
			
		</Response>
	</ResultSpec>
	<ResultSpec id="tag">
		<Query>[Tag]</Query>
		<Response>

			<Output name="title">Dishes tagged as [0.name]</Output>
			<Output name="more_url">reviewsby.us/tag/[0.name]</Output>
			
		</Response>
	</ResultSpec>	
	<?php foreach ($restaurants as $r): ?>

	<DataObject id="<?php echo $r->getStrippedTitle() ?>" type="Restaurant">
		<QueryName value="<?php echo htmlentities(htmlentities($r->getName())) ?>"/>
			<?php foreach (explode('-',  $r->getStrippedTitle()) as $key): ?>
			<QueryName value="<?php echo htmlentities($key); ?>" />
			<?php endforeach ?>
		
		<?php foreach (explode(' ', $r->getName()) as $key): ?>
		<QueryName value="<?php echo htmlentities(htmlentities($key)); ?>" />
		<?php endforeach ?>
		<Attribute name="stripped" value="<?php echo $r->getStrippedTitle() ?>"/>
		<Attribute name="name" value="<?php echo htmlentities(htmlentities($r->getName())) ?>"/>
			
			<Attribute name="rating" value="<?php echo $r->getAverageRating() ?>" />
				<Attribute name="description" value="<?php echo htmlentities(htmlentities(truncate_text(strip_tags($r->getDescription()),70),null,'UTF-8')) ?>"/>
	</DataObject>
	<?php endforeach ?>
	
	<?php foreach ($tags as $tag => $weight): ?>
	<DataObject id="tag_<?php echo $tag ?>" type="Tag">
		<QueryName value="<?php echo $tag ?>" />
		<Attribute name="name" value="<?php echo $tag ?>" />
	</DataObject>
	<?php endforeach ?>
</Results>