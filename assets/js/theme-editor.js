wp.domReady( () => {
    /* Disable non-GDPR-friendly blocks */
	wp.blocks.unregisterBlockType( 'core/avatar' );
    wp.blocks.unregisterBlockType( 'core/rss' );
    
    /* Disable all embed block variants, except the one for local video uploads */
	const allowedEmbedBlocks = [ 'wordpress' ];
	wp.blocks.getBlockVariations('core/embed').forEach(function (blockVariation) {
		if (-1 === allowedEmbedBlocks.indexOf(blockVariation.name)) {
			wp.blocks.unregisterBlockVariation('core/embed', blockVariation.name);
		}
	});

	/* Disable core blocks that have duplicates in GeneratePress */
    wp.blocks.unregisterBlockType( 'core/heading' );
    wp.blocks.unregisterBlockType( 'core/group' );
    wp.blocks.unregisterBlockType( 'core/columns' );
    wp.blocks.unregisterBlockType( 'core/image' );
    wp.blocks.unregisterBlockType( 'core/query' );
    wp.blocks.unregisterBlockType( 'core/buttons' );
    wp.blocks.unregisterBlockType( 'core/button' );

    /* Disable table block because it's absolute garbage (use TablePress!) */
    wp.blocks.unregisterBlockType( 'core/table' );

} );