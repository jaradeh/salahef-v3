<?php

/**
 * Size guide import - WooCommerce Product CSV Import Suite
 */
class ctSizeGuideProductCsvImport
{
	/**
	 * ctSizeGuideProductCsvImport constructor
	 */
	public function __construct()
    {
	    // export
	    add_filter('woocommerce_csv_product_post_columns', array($this, 'filterCsvProductPostColumns'));

	    // import
	    add_filter('woocommerce_csv_product_postmeta_defaults', array($this, 'filterCsvProductPostmetaDefaults'));
    }

	/**
	 * @param $columns
	 *
	 * @return mixed
	 */
	public function filterCsvProductPostColumns( $columns )
    {
	    // adds "_ct_selectsizeguide" column key to be searched for
	    // "meta:_ct_selectsizeguide" is desired column key in output file
	    $columns['_ct_selectsizeguide'] = 'meta:_ct_selectsizeguide';
	    return $columns;
    }

	/**
	 * @param $postmeta
	 */
	public function filterCsvProductPostmetaDefaults( $postmeta )
	{
		// adds "ct_selectsizeguide" key to be searched for
		$postmeta['_ct_selectsizeguide'] =  '';
    }
}

new ctSizeGuideProductCsvImport();