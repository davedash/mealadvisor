<?php use_helper('Javascript');?>
<?php echo javascript_tag("function toggleMarkdownHelp()
 	{
 	  Element.visible('markdown_help') ? Effect.BlindUp('markdown_help') : Effect.BlindDown('markdown_help');
 	  return false;
 	}") 
?>
 	
<div class="in_form">
	<div class="small">basic <?php echo link_to_function('markdown', "toggleMarkdownHelp()") ?> formatting allowed</div>
	<div id="markdown_help" style="display: none">
		<p>Phrase Emphasis</p>

		<pre><code>*italic*   **bold**
		</code></pre>

		<p>Lists:</p>

		<pre><code>
			*   stuff
			* thing
			*   whatchamacallit
			1.  thingy
			2.  thingumajig
			* what's-his-name
		</code></pre>

		<p>Links:</p>

		<pre><code>An [example](http://url.com/ "Title")
		</code></pre>

		<p>Blockquotes</p>

		<pre><code>
			&gt; Email-style angle brackets
			&gt; are used for blockquotes.

			&gt; &gt; And, they can be nested.
		</code></pre>
		<p><?php echo link_to('more syntax', 'http://daringfireball.net/projects/markdown/syntax', 'title=Markdown syntax [daringfireball.net]') ?>
		<p><?php echo link_to_function('hide information', "toggleMarkdownHelp()") ?></p>
	</div>
</div>