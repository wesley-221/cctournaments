<?php
	function do_bbcode_url ($action, $attributes, $content, $params, $node_object) {
		if (!isset ($attributes['default'])) {
			$url = $content;
			$text = htmlspecialchars ($content);
		} else {
			$url = $attributes['default'];
			$text = $content;
		}
		if ($action == 'validate') {
			if (substr ($url, 0, 5) == 'data:' || substr ($url, 0, 5) == 'file:'
			  || substr ($url, 0, 11) == 'javascript:' || substr ($url, 0, 4) == 'jar:') {
				return false;
			}
			return true;
		}
		return '<a href="' . htmlspecialchars($url) . '">' . $text . '</a>';
	}

	// function do_bbcode_box ($action, $attributes, $content, $params, $node_object) {
	// 	if (!isset ($attributes['default'])) {
	// 		$url = "collapsed text";
	// 		$text = htmlspecialchars ($content);
	// 	} else {
	// 		$url = $attributes['default'];
	// 		$text = $content;
	// 	}
	// 	if ($action == 'validate') {
	// 		if (substr ($url, 0, 5) == 'data:' || substr ($url, 0, 5) == 'file:'
	// 		  || substr ($url, 0, 11) == 'javascript:' || substr ($url, 0, 4) == 'jar:') {
	// 			return false;
	// 		}
	// 		return true;
	// 	}
	// 	return 'Header: ' . htmlspecialchars($url) . ', Text: ' . $text;
	// }

	function do_bbcode_notice ($action, $attributes, $content, $params, $node_object) {
		if (!isset ($attributes['default'])) {
			$url = $content;
			$text = htmlspecialchars ($content);
		} else {
			$url = $attributes['default'];
			$text = $content;
		}
		if ($action == 'validate') {
			if (substr ($url, 0, 5) == 'data:' || substr ($url, 0, 5) == 'file:'
			  || substr ($url, 0, 11) == 'javascript:' || substr ($url, 0, 4) == 'jar:') {
				return false;
			}
			return true;
		}
		return '<div class="notice">' . htmlspecialchars($url) . '</div>';
	}

	// function do_bbcode_spoilerbox ($action, $attributes, $content, $params, $node_object) {
	// 	if (!isset ($attributes['default'])) {
	// 		$url = $content;
	// 		$text = htmlspecialchars ($content);
	// 	} else {
	// 		$url = $attributes['default'];
	// 		$text = $content;
	// 	}
	// 	if ($action == 'validate') {
	// 		if (substr ($url, 0, 5) == 'data:' || substr ($url, 0, 5) == 'file:'
	// 		  || substr ($url, 0, 11) == 'javascript:' || substr ($url, 0, 4) == 'jar:') {
	// 			return false;
	// 		}
	// 		return true;
	// 	}
	// 	return 'Header: ' . htmlspecialchars($url) . ', Text: ' . $text;
	// }

	function do_bbcode_font ($action, $attributes, $content, $params, $node_object) {
		if (!isset ($attributes['default'])) {
			$url = $content;
			$text = htmlspecialchars ($content);
		} else {
			$url = $attributes['default'];
			$text = $content;
		}
		if ($action == 'validate') {
			if (substr ($url, 0, 5) == 'data:' || substr ($url, 0, 5) == 'file:'
			  || substr ($url, 0, 11) == 'javascript:' || substr ($url, 0, 4) == 'jar:') {
				return false;
			}
			return true;
		}
		return '<span style="font-size: '.htmlspecialchars ($url).'px;">'.$text.'</span>';
	}

	function do_bbcode_color ($action, $attributes, $content, $params, $node_object) {
		if (!isset ($attributes['default'])) {
			$url = $content;
			$text = htmlspecialchars ($content);
		} else {
			$url = $attributes['default'];
			$text = $content;
		}
		if ($action == 'validate') {
			if (substr ($url, 0, 5) == 'data:' || substr ($url, 0, 5) == 'file:'
			  || substr ($url, 0, 11) == 'javascript:' || substr ($url, 0, 4) == 'jar:') {
				return false;
			}
			return true;
		}
		return '<span style="color: '.htmlspecialchars ($url).';">'.$text.'</span>';
	}

	function do_bbcode_profile ($action, $attributes, $content, $params, $node_object) {
		if (!isset ($attributes['default'])) {
			$url = $content;
			$text = htmlspecialchars ($content);
		} else {
			$url = $attributes['default'];
			$text = $content;
		}
		if ($action == 'validate') {
			if (substr ($url, 0, 5) == 'data:' || substr ($url, 0, 5) == 'file:'
			  || substr ($url, 0, 11) == 'javascript:' || substr ($url, 0, 4) == 'jar:') {
				return false;
			}
			return true;
		}
		return '<a href="https://osu.ppy.sh/u/' . htmlspecialchars($url) . '">' . $text . '</a>';
	}

	function do_bbcode_google ($action, $attributes, $content, $params, $node_object) {
		if (!isset ($attributes['default'])) {
			$url = $content;
			$text = htmlspecialchars ($content);
		} else {
			$url = $attributes['default'];
			$text = $content;
		}
		if ($action == 'validate') {
			if (substr ($url, 0, 5) == 'data:' || substr ($url, 0, 5) == 'file:'
			  || substr ($url, 0, 11) == 'javascript:' || substr ($url, 0, 4) == 'jar:') {
				return false;
			}
			return true;
		}
		return '<a href="https://google.com/search?q=' . htmlspecialchars($url) . '">' . $text . '</a>';
	}

	function do_bbcode_lucky ($action, $attributes, $content, $params, $node_object) {
		if (!isset ($attributes['default'])) {
			$url = $content;
			$text = htmlspecialchars ($content);
		} else {
			$url = $attributes['default'];
			$text = $content;
		}
		if ($action == 'validate') {
			if (substr ($url, 0, 5) == 'data:' || substr ($url, 0, 5) == 'file:'
			  || substr ($url, 0, 11) == 'javascript:' || substr ($url, 0, 4) == 'jar:') {
				return false;
			}
			return true;
		}
		return '<a href="https://google.com/search?q=' . htmlspecialchars($url) . '&btnl=1">' . $text . '</a>';
	}

	function do_bbcode_youtube ($action, $attributes, $content, $params, $node_object) {
		if (!isset ($attributes['default'])) {
			$url = $content;
			$text = htmlspecialchars ($content);
		} else {
			$url = $attributes['default'];
			$text = $content;
		}
		if ($action == 'validate') {
			if (substr ($url, 0, 5) == 'data:' || substr ($url, 0, 5) == 'file:'
			  || substr ($url, 0, 11) == 'javascript:' || substr ($url, 0, 4) == 'jar:') {
				return false;
			}
			return true;
		}

		return '<iframe width="420" height="315" src="https://www.youtube.com/embed/' . $text . '"></iframe>';
	}

	function do_bbcode_img ($action, $attributes, $content, $params, $node_object) {
		if ($action == 'validate') {
			if (substr ($content, 0, 5) == 'data:' || substr ($content, 0, 5) == 'file:'
			  || substr ($content, 0, 11) == 'javascript:' || substr ($content, 0, 4) == 'jar:') {
				return false;
			}
			return true;
		}
		return '<img src="' . htmlspecialchars($content) . '" style="max-width: 100%; height: auto;">';
	}


	$bbcode = new StringParser_BBCode ();
	$bbcode->addFilter (STRINGPARSER_FILTER_PRE, 'convertlinebreaks');
	$bbcode->addParser (array ('block', 'inline', 'link', 'listitem'), 'htmlspecialchars');
	$bbcode->addParser (array ('block', 'inline', 'link', 'listitem'), 'nl2br');
	$bbcode->addCode ('b', 'simple_replace', null, array ('start_tag' => '<b>', 'end_tag' => '</b>'), 'inline', array ('listitem', 'block', 'inline', 'link'), array ('code'));
	$bbcode->addCode ('i', 'simple_replace', null, array ('start_tag' => '<i>', 'end_tag' => '</i>'), 'inline', array ('listitem', 'block', 'inline', 'link'), array ('code'));
	$bbcode->addCode ('u', 'simple_replace', null, array ('start_tag' => '<u>', 'end_tag' => '</u>'), 'inline', array ('listitem', 'block', 'inline', 'link'), array ('code'));
	$bbcode->addCode ('strike', 'simple_replace', null, array ('start_tag' => '<strike>', 'end_tag' => '</strike>'), 'inline', array ('listitem', 'block', 'inline', 'link'), array ('code'));
	$bbcode->addCode ('centre', 'simple_replace', null, array ('start_tag' => '<center>', 'end_tag' => '</center>'), 'inline', array ('listitem', 'block', 'inline', 'link'), array ('code'));
	$bbcode->addCode ('heading', 'simple_replace', null, array ('start_tag' => '<div class="tournamenttitle">', 'end_tag' => '</div>'), 'inline', array ('listitem', 'block', 'inline', 'link'), array ('code'));
	$bbcode->addCode ('code', 'simple_replace', null, array ('start_tag' => '<textarea rows="8" cols="80" class="codeBlock">', 'end_tag' => '</textarea>'), '', array ('listitem', 'block', 'inline', 'link'), array ('code'));
	$bbcode->addCode ('spoiler', 'simple_replace', null, array ('start_tag' => '<span class="spoiler">', 'end_tag' => '</span>'), 'inline', array ('listitem', 'block', 'inline', 'link'), array ('code'));

	$bbcode->addCode ('notice', 'simple_replace', null, array ('start_tag' => '<div class="notice">', 'end_tag' => '</div>'), 'block', array ('listitem', 'block', 'inline', 'link'), array ('code'));

	$bbcode->addCode ('profile', 'usecontent', 'do_bbcode_profile', array(), 'link', array ('listitem', 'block', 'inline', 'link'), array ('link', 'code'));
	$bbcode->addCode ('google',  'usecontent', 'do_bbcode_google',  array(), 'link', array ('listitem', 'block', 'inline', 'link'), array ('link', 'code'));
	$bbcode->addCode ('lucky',   'usecontent', 'do_bbcode_lucky',   array(), 'link', array ('listitem', 'block', 'inline', 'link'), array ('link', 'code'));
	$bbcode->addCode ('youtube', 'usecontent', 'do_bbcode_youtube', array(), 'link', array ('listitem', 'block', 'inline', 'link'), array ('link', 'code'));

	$bbcode->addCode ('url', 'usecontent?', 'do_bbcode_url', array ('usecontent_param' => 'default'), 'link', array ('listitem', 'block', 'inline'), array ('link', 'code'));
	// $bbcode->addCode ('box', 'usecontent?', 'do_bbcode_box', array ('usecontent_param' => 'default'), 'block', array ('listitem', 'block', 'inline'), array ('link', 'code'));
	// $bbcode->addCode ('spoilerbox', 'usecontent', 'do_bbcode_spoilerbox', array (), 'block', array ('listitem', 'block', 'inline'), array ('link', 'code'));

	$bbcode->addCode ('img', 'usecontent', 'do_bbcode_img', array (), 'image', array ('listitem', 'block', 'inline', 'link'), array ('code'));
	$bbcode->addCode ('size', 'usecontent?', 'do_bbcode_font', array (), 'font', array ('listitem', 'block', 'inline', 'link'), array ('code'));
	$bbcode->addCode ('color', 'usecontent?', 'do_bbcode_color', array (), 'font', array ('listitem', 'block', 'inline', 'link'), array ('code'));

	$bbcode->addCode ('list', 'simple_replace', null, array ('start_tag' => '<ul>', 'end_tag' => '</ul>'), 'list', array ('block', 'inline', 'link'), array ('code'));
	$bbcode->addCode ('*', 'simple_replace', null, array ('start_tag' => '<li>', 'end_tag' => '</li>'), 'listitem', array ('list'), array ('code'));
	$bbcode->setCodeFlag ('*', 'closetag', BBCODE_CLOSETAG_OPTIONAL);
	$bbcode->setCodeFlag ('list', 'paragraph_type', BBCODE_PARAGRAPH_BLOCK_ELEMENT);
	$bbcode->setCodeFlag ('list', 'opentag.before.newline', BBCODE_NEWLINE_DROP);
	$bbcode->setCodeFlag ('list', 'closetag.before.newline', BBCODE_NEWLINE_DROP);
	$bbcode->setRootParagraphHandling (true);
?>
