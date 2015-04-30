<?php

class EZClipboard extends CWidget {

	// The tag type to use 'Defaults to button'
	public $tag = 'button';
	// Tag htmlOptions
	public $tagHtmlOptions = array();
	// Content to display inbetween the tag
	public $tagContent = "Copy Me";
	// Whether to generate a close tag
	public $closeTag = true;
	// The ID of the HTML element to be copied
	public $tagId = "copy_button";
	// ZeroClipboard options in array form (i.e. array('moviePath'=>'....'))
	public $zcOptions = array();
	// Any events you want attached in array form (i.e. array('load'=>'onLoad', 'complete'=>'onComplete'))
	public $zcEvents;
	// The data-clipboard-text that will be copied to the clipboard
	public $clipboardText = "Text to Copy";
	// Position of the script (Default: CClientScript::POS_BEGIN)
	public $scriptPos = "END";

	// Flash file name
	protected $swfFile = 'ZeroClipboard.swf';
	// Javascript files to register
	protected $jsFiles = array('ZeroClipboard.js');
	// The asset path
	protected $assetPath;

	/**
	 * Get a CClientScript Position
	 * @param  string the position string ('begin', 'end', 'head', 'load', 'ready')
	 * @return integer CClientScript position integer
	 */
	protected function getScriptPos($pos) {
		return constant('CClientScript::POS_' . strtoupper($pos));
	}

	/**
	 * Register all necessary scripts
	 */
	protected function registerScripts() {
		
		// Set paths
		$this->assetPath = Yii::app()->getAssetManager()->publish( dirname( __FILE__ ) . '/lib' );

		// Ensure jQuery is registered
		$clientScript = Yii::app()->clientScript;
		if( !$clientScript->isScriptRegistered( 'jquery' ) ) {
			$clientScript->addPackage('jquery', array(
				'baseUrl'	=> 'https://ajax.googleapis.com/ajax/libs/jquery/1.8.3',
				'js'		=> array('jquery.min.js')
			));
		}

		// Register all javascript files
		foreach( $this->jsFiles as $file ) {
			$clientScript->registerScriptFile( $this->assetPath . '/js/' . $file,  $this->getScriptPos($this->scriptPos));
		}
	}

	/**
	 * Initialize the widget
	 */
	public function init() {
		$this->registerScripts();
		parent::init();
	}

	/**
	 * Run the widget
	 */
	public function run() {
		$this->zcOptions['moviePath'] = $this->assetPath . '/swf/' . $this->swfFile;
		$script = "var clip = new ZeroClipboard($('#{$this->tagId}'), " . json_encode($this->zcOptions) . ");";
		if (@$this->zcEvents) {
			foreach($this->zcEvents as $event=>$function) 
				$script .= "clip.on('$event', '$function');";
		}
		$script .= "$(clip.htmlBridge).tooltip({title: '".$this->tagHtmlOptions['data-placement-title']."', placement: '".$this->tagHtmlOptions['data-placement-tooltip']."'});";
		
		Yii::app()->clientScript->registerScript($this->id, $script, CClientScript::POS_READY);

		$this->tagHtmlOptions['id'] = $this->tagId;
		$this->tagHtmlOptions['data-clipboard-text'] = $this->clipboardText;
		echo CHtml::tag($this->tag, $this->tagHtmlOptions, $this->tagContent, $this->closeTag);
	}
}
