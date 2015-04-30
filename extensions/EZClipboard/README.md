EEZClipboard
==========

This is a ZeroClipboard extension for the Yii Framework.

# Installing

* Download the EZClipboard zip file by clicking the ZIP button in the master repo
* Unzip the file and copy it to the /protected/extensions/ directory in your Yii app

# Usage

	$this->widget('ext.EZClipboard.EZClipboard, array(
		'tag' => 'a',
		'tagHtmlOptions' => array('class'=>'copy-class'),
		'tagId' => 'copy_button',
		'clipboardText' => 'This is the text that will be copied'
	));
	
## Example

	<html>
		<head>
			<script type="text/javascript">
				function onLoad() {
					console.log('Movie Loaded');
				}

				function onComplete(client, args) {
	  				console.log("Copied text to clipboard: " + args.text );
				}
			</script>
		</head>
		<body>
			<? 
				$this->widget('ext.EZClipboard.EZClipboard, array(
					'tagHtmlOptions' 	=> array('class'=>'copy-class'),
					'tagId' 			=> 'copy_button',
					'tagContent' 	 	=> "Copy Text",
					'clipboardText'		=> 'This is the text that will be copied',
					'zcEvents' 			=> array('load'=>'onLoad', 'complete'=>'onComplete'),
					'scriptPos'			=> 'HEAD'
				));
			?>			
		</body>
	</html>
	
Would produce the following code:

	<html>
		<head>
			<script type="text/javascript">
				function onLoad() {
					console.log('Movie Loaded');
				}

				function onComplete(client, args) {
	  				console.log("Copied text to clipboard: " + args.text );
				}
			</script>
			<script type="text/javascript" src="/assets/5fdfd183/js/ZeroClipboard.js"></script>
		</head>
		<body>
			<button class="copy-class" id="copy" data-clipboard-text="This is the text that will be copied">Copy Text</button>
			<script type="text/javascript">
				/*<![CDATA[*/
				jQuery(function($) {var clip = new ZeroClipboard($('#copy'), {"moviePath":"\/assets\/5fdfd183\/swf\/ZeroClipboard.swf"});clip.on('load', 'onLoad');clip.on('complete', 'onComplete');});
				/*]]>*/
				</script>	
		</body>
	</html>

## Options
  
**tag** - the type of tag to use (Default: 'button')  

	"tag" => "a"

**tagHtmlOptions** - the htmlOptions for the tag (i.e. 'id', 'class', etc.)  

	"tagHtmlOptions" => array(
		"class" => "copy_class"
	)

**tagContent** - the content between the tags    
	
	"tagContent" => "Copy Text"

**closeTag** - whether or not to use a closing tag  

	"tagContent" => false

**tagId** - shortcut for the tag ID, could also use tagHtmlOptions   

	"tagId" => "copy_button"

**zcOptions** - ZeroClipboard options 
	
	"zcOptions" => array('moviePath'=>'....')  

**zcEvents** - ZeroClipboard events in an array 
	
	"zcEvents" => array('load'=>'onLoad')  

**clipboardText** - the text that will be copied when the user clicks the movie   

	"tagContent" => "This is the text that will be copied"

**scriptPos** - the position of the scripts (Default: 'END')   

	"scriptPos" => "HEAD"
