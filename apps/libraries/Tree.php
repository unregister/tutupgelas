<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * $tree = new Tree;
 * $tree->add_row(1, 0, '', 'Menu 1');
 * $tree->add_row(2, 0, '', 'Menu 2');
 * $tree->add_row(3, 1, '', 'Menu 1.1');
 * $tree->add_row(4, 1, '', 'Menu 1.2');
 * echo $tree->generate_list();
 *
 * output:
 * <ul>
 * 	<li>Menu 1
 * 		<ul>
 * 			<li>Menu 1.1</li>
 * 			<li>Menu 1.2</li>
 * 		</ul>
 * 	</li>
 * 	<li>Menu 2</li>
 * </ul>
 *
 */
class Tree
{
	var $data;

	function add_row($id, $parent, $li_attr, $label) {
		$this->data[$parent][] = array('id' => $id, 'li_attr' => $li_attr, 'label' => $label);
	}

	function generate_list($ul_attr = '') {
		return $this->ul(0, $ul_attr);
	}

	function ul($parent = 0, $attr = '') {
		static $i = 1;
		$indent = str_repeat("\t\t", $i);
		if (isset($this->data[$parent])) {
			if ($attr) {
				$attr = ' ' . $attr;
			}
			$html = "\n$indent";
			$html .= "<ul$attr>";
			$i++;
			foreach ($this->data[$parent] as $row) {
				$child = $this->ul($row['id']);
				$html .= "\n\t$indent";
				$html .= '<li'. $row['li_attr'] . '>';
				$html .= $row['label'];
				if ($child) {
					$i--;
					$html .= $child;
					$html .= "\n\t$indent";
				}
				$html .= '</li>';
			}
			$html .= "\n$indent</ul>";
			return $html;
		} else {
			return false;
		}
	}

	function clear() {
		$this->data = array();
	}
}