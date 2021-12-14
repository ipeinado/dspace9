<?php

namespace Drupal\masterlist\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Creates the a block with TOC and filters for the masterlist
 *
 * @Block(
 *	id = "masterlist_block",
 *	admin_label = @Translation("Masterlist Block"),
 *	category = @Translation("Masterlist Block"),
 * )
 */
class MasterlistBlock extends BlockBase {
	/**
	 * {@inheritdoc}
	 */
	public function build() {
		return array(
			'#theme' => 'masterlist_toc',
		);
	}
}