<?php
/**
 * Demo content for the Quillmoor concept site. Safe to run once on a fresh WordPress install.
 * Run with: wp eval-file seed.php
 */
defined( 'ABSPATH' ) || exit;

if ( get_option( 'quillmoor_seeded' ) ) {
	return;
}

require_once ABSPATH . 'wp-admin/includes/image.php';

wp_set_current_user( 1 );

// The browser demo skips extra image sizes so it opens quickly.
if ( defined( 'QUILLMOOR_FAST_SEED' ) ) {
	add_filter( 'intermediate_image_sizes_advanced', '__return_empty_array' );
}

// Remove the sample content WordPress installs.
foreach ( array( 'hello-world' => 'post', 'sample-page' => 'page', 'privacy-policy' => 'page' ) as $slug => $type ) {
	$sample = get_page_by_path( $slug, OBJECT, $type );
	if ( $sample ) {
		wp_delete_post( $sample->ID, true );
	}
}

function qm_image( $name, $alt ) {
	$source = __DIR__ . '/img/' . $name . '.webp';
	$upload = wp_upload_bits( $name . '.webp', null, file_get_contents( $source ) ); // phpcs:ignore
	$id     = wp_insert_attachment(
		array(
			'post_mime_type' => 'image/webp',
			'post_title'     => $name,
			'post_status'    => 'inherit',
		),
		$upload['file']
	);
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
	update_post_meta( $id, '_wp_attachment_image_alt', $alt );
	return $id;
}

function qm_img_block( $id, $alt, $class = '' ) {
	$class_attr = $class ? ',"className":"' . $class . '"' : '';
	$figure     = trim( 'wp-block-image size-full ' . $class );
	return '<!-- wp:image {"id":' . $id . ',"sizeSlug":"full","linkDestination":"none"' . $class_attr . '} --><figure class="' . $figure . '"><img src="' . esc_url( wp_get_attachment_url( $id ) ) . '" alt="' . esc_attr( $alt ) . '" class="wp-image-' . $id . '"/></figure><!-- /wp:image -->';
}

function qm_p( $text, $class = '' ) {
	$attrs = $class ? ' {"className":"' . $class . '"}' : '';
	$cls   = $class ? ' class="' . $class . '"' : '';
	return "<!-- wp:paragraph$attrs --><p$cls>$text</p><!-- /wp:paragraph -->\n";
}

function qm_h( $text, $level = 2 ) {
	$attrs = 2 === $level ? '' : ' {"level":' . $level . '}';
	return "<!-- wp:heading$attrs --><h$level class=\"wp-block-heading\">$text</h$level><!-- /wp:heading -->\n";
}

function qm_group( $class, $inner, $tag = 'div' ) {
	// Top-level sections run edge to edge, in the editor as well as on the site.
	$full      = 'section' === $tag;
	$tag_attr  = 'div' === $tag ? '' : '"tagName":"' . $tag . '",';
	$full_attr = $full ? '"align":"full",' : '';
	$classes   = 'wp-block-group ' . ( $full ? 'alignfull ' : '' ) . $class;
	return '<!-- wp:group {' . $tag_attr . $full_attr . '"className":"' . $class . '","layout":{"type":"default"}} --><' . $tag . ' class="' . $classes . '">' . $inner . '</' . $tag . '><!-- /wp:group -->' . "\n";
}

function qm_page( $title, $slug, $content ) {
	return wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => $content,
		)
	);
}

// Images.
$alts = array(
	'hero-apr'             => 'The garden in April: grasses cut back, daffodils along the stone wall',
	'hero-jul'             => 'The garden in July: coneflowers and tall grasses in full growth',
	'hero-oct'             => 'The garden in October: orange maple, copper grasses, purple asters',
	'hero-jan'             => 'The garden in January: seedheads standing above light snow',
	'proj-katonah-lead'    => 'A mown path through a tall native meadow toward a stone wall',
	'proj-katonah-detail'  => 'Grasses and mountain mint at the edge of the meadow',
	'proj-poundridge-lead' => 'A bluestone courtyard beside a stone outbuilding with boxwood and loose grasses',
	'proj-poundridge-detail' => 'Bluestone steps with fallen leaves beside a copper rain chain',
	'proj-northsalem-lead' => 'Raised beds and an espaliered apple on a stone wall in spring',
	'proj-northsalem-detail' => 'Gloved hands setting seedlings into dark soil',
	'proj-bedford-lead'    => 'A gravel woodland walk beside a stone wall, dusted with snow',
	'proj-bedford-detail'  => 'Hellebores flowering through snow',
	'process-site-visit'   => 'A designer with a notebook walking a sloped lawn toward a white house',
	'process-plan'         => 'A colored pencil planting plan on a wooden table',
	'journal-cutback'      => 'Bundles of cut grass stems on a gravel path in late March',
	'journal-stonewall'    => 'A repaired dry-laid stone wall beside an old one',
	'journal-winterberry'  => 'Winterberry branches heavy with red berries in snow',
);
$img = array();
foreach ( $alts as $name => $alt ) {
	$img[ $name ] = qm_image( $name, $alt );
}

// Gardens, newest first so the home page shows them in this order.
$gardens = array(
	array(
		'Katonah meadow edge', 'proj-katonah-lead', 'proj-katonah-detail', 'Katonah', 'Two acres', 'Pycnanthemum muticum', 'july', 'Best in July',
		'The owners mowed two acres every week and never walked on most of it. We took the far half out of lawn, sowed a native meadow and kept one mown path through it, so the place now reads as a choice and not as neglect.',
		'The meadow took three years to settle. The first summer is mostly annual weeds, which we warned the owners about in writing. Mown once each March, it now carries bluestem, mountain mint and coneflower from June until snow.',
	),
	array(
		'Pound Ridge courtyard', 'proj-poundridge-lead', 'proj-poundridge-detail', 'Pound Ridge', 'A quarter acre', 'Panicum virgatum', 'october', 'Best in October',
		'A small space between the house and an old stone outbuilding, where the rain came off the roof and went nowhere. We relaid the bluestone to shed water, added a copper rain chain and planted clipped boxwood among loose switchgrass.',
		'The boxwood holds the shape all winter. The grass moves all fall. It is the garden we show people who think a small yard has to be a tidy one.',
	),
	array(
		'North Salem kitchen garden', 'proj-northsalem-lead', 'proj-northsalem-detail', 'North Salem', 'Half an acre', 'Rheum × hybridum', 'april', 'Best in April',
		'Raised cedar beds close enough to the kitchen door that nobody has to put on boots for a handful of herbs. An espaliered apple runs along the south face of the old stone wall. The stone holds heat and brings the bloom on early, so we chose a late-flowering variety to stay ahead of a hard frost.',
		'The deer fence runs behind the wall line, and a new wooden gate closes the gap. The beds are sized so that one person can reach the middle from either side.',
	),
	array(
		'Bedford winter walk', 'proj-bedford-lead', 'proj-bedford-detail', 'Bedford', 'One acre', 'Ilex verticillata', 'january', 'Best in January',
		'The owners see this path from the breakfast table, and they asked for something to look at from November to March. Winterberry, red-twig dogwood and hellebores line a gravel walk along the existing wall.',
		'The hellebores sit under the existing oaks. The winterberry and dogwood stand in the open stretch of the walk, where they get the sun they need to color up. Most of it sits still in summer, by design.',
	),
);
$row = static function ( $name, $latin, $months, $season ) {
	return array( 'name' => $name, 'latin' => $latin, 'months' => $months, 'season' => $season );
};
$charts = array(
	'Katonah meadow edge' => array(
		$row( 'Golden Alexanders', 'Zizia aurea', '5,6', 'april' ),
		$row( 'Black-eyed Susan', 'Rudbeckia hirta', '6,7,8,9', 'october' ),
		$row( 'Mountain mint', 'Pycnanthemum muticum', '7,8,9', 'july' ),
		$row( 'Purple coneflower', 'Echinacea purpurea', '7,8,9', 'july' ),
		$row( 'New England aster', 'Symphyotrichum novae-angliae', '9,10', 'october' ),
		$row( 'Little bluestem', 'Schizachyrium scoparium', '8,9,10,11,12,1,2', 'october' ),
	),
	'Pound Ridge courtyard' => array(
		$row( 'Boxwood', 'Buxus sempervirens', '1,2,3,4,5,6,7,8,9,10,11,12', 'july' ),
		$row( 'Threadleaf bluestar', 'Amsonia hubrichtii', '5,10,11', 'october' ),
		$row( 'New England aster', 'Symphyotrichum novae-angliae', '9,10', 'october' ),
		$row( 'Switchgrass', 'Panicum virgatum', '8,9,10,11,12,1,2', 'october' ),
	),
	'North Salem kitchen garden' => array(
		$row( 'Daffodil', 'Narcissus', '4', 'april' ),
		$row( 'Rhubarb', 'Rheum × hybridum', '4,5,6', 'april' ),
		$row( 'Chives', 'Allium schoenoprasum', '5,6', 'april' ),
		$row( 'Espaliered apple', 'Malus domestica', '5,9,10', 'october' ),
		$row( 'Kale', 'Brassica oleracea', '6,7,8,9,10,11,12', 'july' ),
	),
	'Bedford winter walk' => array(
		$row( 'Vernal witch hazel', 'Hamamelis vernalis', '2,3', 'january' ),
		$row( 'Lenten rose', 'Helleborus × hybridus', '3,4', 'january' ),
		$row( 'Red-twig dogwood', 'Cornus sericea', '11,12,1,2,3', 'october' ),
		$row( 'Winterberry', 'Ilex verticillata', '10,11,12,1,2', 'january' ),
	),
);
$date = strtotime( '2026-10-20 10:00:00' );
foreach ( $gardens as $i => $g ) {
	$content  = qm_group( 'garden-split', qm_group( 'garden-text', qm_p( $g[8] ) . qm_p( $g[9] ) ) . qm_img_block( $img[ $g[2] ], $alts[ $g[2] ] ) ) .
		qm_h( 'This garden, month by month' ) .
		'<!-- wp:quillmoor/bloom-chart ' . wp_json_encode( array( 'rows' => $charts[ $g[0] ] ), JSON_UNESCAPED_UNICODE ) . ' /-->';
	$id       = wp_insert_post(
		array(
			'post_type'    => 'quill_garden',
			'post_status'  => 'publish',
			'post_title'   => $g[0],
			'post_content' => $content,
			'post_date'    => gmdate( 'Y-m-d H:i:s', $date - $i * DAY_IN_SECONDS ),
		)
	);
	set_post_thumbnail( $id, $img[ $g[1] ] );
	update_post_meta( $id, 'garden_town', $g[3] );
	update_post_meta( $id, 'garden_size', $g[4] );
	update_post_meta( $id, 'garden_plant', $g[5] );
	update_post_meta( $id, 'garden_season', $g[6] );
	update_post_meta( $id, 'garden_best', $g[7] );
}

// Journal.
$posts = array(
	array(
		'Leave the grasses up until March', 'journal-cutback', '2026-03-12',
		array(
			'Cutting ornamental grasses in October is a habit left over from when tidy was the only goal. Leave them standing. Little bluestem and switchgrass hold their shape through most of a Westchester winter, catch the snow, and give you something to look at from the kitchen window.',
			'They also shelter the insects that will feed the birds in spring. Many native bees and beneficial insects spend the winter in hollow stems and leaf litter.',
			'Cut them in March, before the new growth starts. Take them down to about six inches, and stack the bundles out of the way until May, so whatever spent the winter in them can get out. Then compost them. If you wait until the new blades are up, you will cut those too.',
		),
	),
	array(
		'Your old stone wall is worth repairing', 'journal-stonewall', '2026-05-04',
		array(
			'Most of the fieldstone walls in northern Westchester were built dry, with no mortar, and that is why so many of them are still standing. Frost moves the ground every winter, and a dry wall moves with it and settles. A mortared wall on the same ground tends to crack.',
			'When a section slumps, take it down to sound stone and rebuild that length dry, using the same stone. Most of what you need is usually lying at the foot of the wall.',
			'A rebuilt section will look clean for years, and lichen is slower than most owners expect. Leave it alone and it will catch up.',
		),
	),
	array(
		'Why you need a male winterberry', 'journal-winterberry', '2026-11-02',
		array(
			'Winterberry holly is the best red in a January garden, and it is the plant people most often call us about when it has never fruited. Usually the plant is healthy and simply alone.',
			'<em>Ilex verticillata</em> has separate male and female plants, and only pollinated females carry berries. Plant one male within reach of several females, and match bloom times: \'Jim Dandy\' flowers early and suits early females such as \'Red Sprite\', while \'Southern Gentleman\' flowers later and suits \'Winter Red\'.',
			'The male does not need to be a feature. Tuck it at the back, where its plainer winter look will not matter.',
		),
	),
);
foreach ( $posts as $p ) {
	$content = '';
	foreach ( $p[3] as $para ) {
		$content .= qm_p( $para );
	}
	$id = wp_insert_post(
		array(
			'post_status'  => 'publish',
			'post_title'   => $p[0],
			'post_content' => $content,
			'post_date'    => $p[2] . ' 09:00:00',
		)
	);
	set_post_thumbnail( $id, $img[ $p[1] ] );
}

// Home page: real blocks, editable in Pages > Home.
$seasons = array(
	array( 'id' => 'april', 'label' => 'April', 'plant' => 'Narcissus', 'imageId' => $img['hero-apr'], 'alt' => $alts['hero-apr'] ),
	array( 'id' => 'july', 'label' => 'July', 'plant' => 'Echinacea purpurea', 'imageId' => $img['hero-jul'], 'alt' => $alts['hero-jul'] ),
	array( 'id' => 'october', 'label' => 'October', 'plant' => 'Schizachyrium scoparium', 'imageId' => $img['hero-oct'], 'alt' => $alts['hero-oct'] ),
	array( 'id' => 'january', 'label' => 'January', 'plant' => 'Ilex verticillata', 'imageId' => $img['hero-jan'], 'alt' => $alts['hero-jan'] ),
);
$hero_copy = qm_group(
	'hero-copy',
	qm_group(
		'hero-text',
		'<!-- wp:heading {"level":1} --><h1 class="wp-block-heading">Gardens that hold up in every month.</h1><!-- /wp:heading -->' .
		qm_p( 'Quillmoor designs and plants gardens for older houses in northern Westchester. We plan the cold months first, so January looks as considered as July.', 'hero-lede' ) .
		'<!-- wp:quillmoor/visit-start /-->'
	)
);
$hero = qm_group( 'hero', $hero_copy . '<!-- wp:quillmoor/season-strip ' . wp_json_encode( array( 'seasons' => $seasons ), JSON_UNESCAPED_UNICODE ) . ' /-->', 'section' );

$chart = qm_group(
	'sect',
	qm_group( 'sect-head', qm_h( 'We draw the plan across twelve months.' ) . qm_p( 'Most gardens are designed for one good week in June. Every Quillmoor plan comes with a chart like this one, so you can see what is carrying the garden in February before anything goes in the ground.' ) ) .
	'<!-- wp:quillmoor/bloom-chart /-->',
	'section'
);

$query_gardens = '<!-- wp:query {"query":{"perPage":4,"postType":"quill_garden","order":"desc","orderBy":"date","inherit":false}} --><div class="wp-block-query"><!-- wp:post-template {"className":"gardens"} --><!-- wp:post-featured-image /--><!-- wp:post-title {"level":3,"isLink":true} /--><!-- wp:group {"className":"packet-meta","layout":{"type":"default"}} --><div class="wp-block-group packet-meta"><!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"core/post-meta","args":{"key":"garden_plant"}}}},"className":"garden-plant"} --><p class="garden-plant"></p><!-- /wp:paragraph --><!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"core/post-meta","args":{"key":"garden_best"}}}}} --><p></p><!-- /wp:paragraph --></div><!-- /wp:group --><!-- /wp:post-template --></div><!-- /wp:query -->';

$gardens_section = qm_group(
	'sect no-top',
	qm_group( 'sect-head', qm_h( 'Four gardens, each at its best.' ) . qm_p( 'Every garden has a month it was built around, and it still has to look right in the other eleven.' ) ) . $query_gardens,
	'section'
);

$step = static function ( $image, $title, $text ) use ( $img, $alts ) {
	return qm_group( 'step', qm_img_block( $img[ $image ], $alts[ $image ], 'ph' ) . qm_h( $title, 3 ) . qm_p( $text ) );
};
$steps = qm_group(
	'steps',
	$step( 'process-site-visit', 'We walk it with you', 'Two hours on site, in whatever the weather is doing. We look at the light, the drainage, the views you want to keep and the ones you would rather lose.' ) .
	$step( 'process-plan', 'We draw it for the year', 'A scaled planting plan, a twelve-month chart and a fixed price for the installation. You can change anything until you are happy with it.' ) .
	$step( 'proj-northsalem-detail', 'We plant it and come back', 'Our crew plants in spring or fall. We return each season in the first year to move what landed wrong and replace what did not take.' )
);
$process = qm_group(
	'sect process',
	qm_group( 'sect-head', qm_h( 'How a garden gets made.' ) . qm_p( 'Nobody digs until you have seen the plan and the chart, and you know what it will cost.' ) ) . $steps,
	'section'
);

$query_posts = '<!-- wp:query {"query":{"perPage":3,"postType":"post","order":"desc","orderBy":"date","inherit":false}} --><div class="wp-block-query"><!-- wp:post-template {"className":"posts"} --><!-- wp:post-featured-image {"isLink":true} /--><!-- wp:post-title {"level":3,"isLink":true} /--><!-- wp:post-date {"format":"F Y"} /--><!-- /wp:post-template --></div><!-- /wp:query -->';

$journal = qm_group(
	'sect',
	qm_group( 'sect-head', qm_h( 'From the journal.' ) . qm_p( 'Notes on what to do, and what to leave alone.' ) ) . $query_posts,
	'section'
);

$site_visit_url = home_url( '/site-visit/' );
$close          = qm_group(
	'close',
	qm_h( 'Show us the yard you have.' ) .
	'<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $site_visit_url ) . '">Request a site visit</a></div><!-- /wp:button --></div><!-- /wp:buttons -->',
	'section'
);

$home_id = qm_page( 'Home', 'home', $hero . $chart . $gardens_section . $process . $journal . $close );

// Other pages.
$service = static function ( $title, $text ) {
	return qm_group( 'service-row', qm_h( $title ) . qm_p( $text ) );
};
$services = qm_p( 'We take on a small number of gardens each year, all within about thirty minutes of Katonah. Most clients start with the first of these and decide about the rest once they have seen the plan.' ) .
	$service( 'Site visit', 'Two hours walking the property with you. You leave with a clear list of what is working, what is not, and whether a full plan makes sense.' ) .
	$service( 'Garden plan', 'A scaled planting plan, a twelve-month chart and a plant list with quantities. Yours to keep, whoever ends up installing it.' ) .
	$service( 'Planting', 'Our own crew plants in spring or fall, and we are on site for every day of it. The installation price is fixed before we start.' ) .
	$service( 'First-year care', 'Four visits across the first year to check the watering, edit the planting and replace anything that did not take. Gardens get decided in the first year, and we would rather be there for it.' );
$services_id = qm_page( 'What you can hire us to do.', 'services', $services );

$process_page = qm_p( 'Nobody digs until you have seen the plan and the chart, and you know what it will cost.' ) .
	qm_h( 'We walk it with you' ) . qm_p( 'Two hours on site, in whatever the weather is doing. We look at the light, the drainage, the views you want to keep and the ones you would rather lose.' ) . qm_img_block( $img['process-site-visit'], $alts['process-site-visit'] ) .
	qm_h( 'We draw it for the year' ) . qm_p( 'A scaled planting plan, a twelve-month chart and a fixed price for the installation. You can change anything until you are happy with it.' ) . qm_img_block( $img['process-plan'], $alts['process-plan'] ) .
	'<!-- wp:quillmoor/bloom-chart /-->' .
	qm_h( 'We plant it and come back' ) . qm_p( 'Our crew plants in spring or fall. We return each season in the first year to move what landed wrong and replace what did not take.' );
$process_id = qm_page( 'How a garden gets made.', 'process', $process_page );

$visit = qm_p( 'Tell us where the garden is and what is bothering you about it. We will reply within two working days with a time to come and walk it.' ) .
	qm_p( 'This is a concept site, so no real studio will get your message. Please try the form, but do not expect a reply.', 'concept-note' ) .
	'<!-- wp:quillmoor/visit-form /-->';
$visit_id = qm_page( 'Request a site visit', 'site-visit', $visit );
set_post_thumbnail( $visit_id, $img['process-site-visit'] );

$journal_id = qm_page( 'Journal', 'journal', '' );

// Settings.
update_option( 'blogname', 'Quillmoor' );
update_option( 'blogdescription', 'Garden design and planting in northern Westchester' );
update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $home_id );
update_option( 'page_for_posts', $journal_id );
update_option( 'permalink_structure', '/%postname%/' );
flush_rewrite_rules();

// Menu.
$page_link = static function ( $label, $id, $class = '' ) {
	$attrs = array( 'label' => $label, 'type' => 'page', 'id' => $id, 'url' => get_permalink( $id ), 'kind' => 'post-type' );
	if ( $class ) {
		$attrs['className'] = $class;
	}
	return '<!-- wp:navigation-link ' . wp_json_encode( $attrs ) . ' /-->';
};
wp_insert_post(
	array(
		'post_type'    => 'wp_navigation',
		'post_status'  => 'publish',
		'post_title'   => 'Main menu',
		'post_content' => '<!-- wp:navigation-link ' . wp_json_encode( array( 'label' => 'Gardens', 'url' => home_url( '/gardens/' ), 'kind' => 'custom' ) ) . ' /-->' . $page_link( 'Services', $services_id ) . $page_link( 'Process', $process_id ) . $page_link( 'Journal', $journal_id ) . $page_link( 'Request a visit', $visit_id, 'nav-cta' ),
	)
);

update_option( 'quillmoor_seeded', 1 );
