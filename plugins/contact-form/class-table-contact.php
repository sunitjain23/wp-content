<?php
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Contact_List_Table extends WP_List_Table {
  function __construct(){
    global $status, $page;
        parent::__construct( array(
            'singular'  => __( 'Contact', 'glx' ),
            'plural'    => __( 'Contacts', 'glx' ),
            'ajax'      => false
    ) );
    add_action( 'admin_head', array( &$this, 'admin_header' ) );
  }
  function admin_header() {
    $page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
    if( 'contacts' != $page )
    return;
    echo '<style type="text/css">';
    echo '.wp-list-table .column-id { width: 5%; }';
    echo '.wp-list-table .column-booktitle { width: 40%; }';
    echo '.wp-list-table .column-author { width: 35%; }';
    echo '.wp-list-table .column-isbn { width: 20%;}';
    echo '</style>';
  }
  function no_items() {
    _e( 'No books found, dude.' );
  }
  function column_default( $item, $column_name ) {
    switch( $column_name ) { 
      case 'image':
        return  '<img width="50" height="50" src="'.$item[$column_name].'"/>';
      break;
      return $item[$column_name];
      default:
      return $item[$column_name];
    }
  }
  function get_sortable_columns() {
    $sortable_columns = array(
      'first_name' => array( 'first_name', true ),
      'last_name'=>'last_name',
      'email'=>'email',
    );
    return $sortable_columns;
  }
  function get_columns(){
    $columns = array(
      'cb'        => '<input type="checkbox" />',
      'first_name'    => __( 'First Name', 'glx' ),
      'last_name'  => __( 'Last Name', 'glx' ),       
      'email'  => __( 'Email', 'glx' ),       
      'subject'  => __( 'Subject', 'glx' ),       
      'image'  => __( 'Image', 'glx' ),       
      'comments' => _x( 'Comments', 'column name', 'glx' ),
    );
    return $columns;
  }
  function usort_reorder( $a, $b ) {
    $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'id';
    $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
    $result = strcmp( $a[$orderby], $b[$orderby] );
    return ( $order === 'asc' ) ? $result : -$result;
  }

  function column_cb($item) {
    return sprintf(
      '<input type="checkbox" name="contact[]" value="%s" />', $item['id']
    );    
  }

  function prepare_items() {
    $user_search_key = isset( $_REQUEST['s'] ) ? wp_unslash( trim( $_REQUEST['s'] ) ) : '';
    $users_per_page = $this->get_items_per_page( 'users_per_page' );
    $table_page = $this->get_pagenum(); 
    $this->_column_headers = $this->get_column_info();     
    $table_data = $this->fetch_table_data();
    if( $user_search_key ) {
        $table_data = $this->filter_table_data( $table_data, $user_search_key );
    }
    $this->items = array_slice( $table_data, ( ( $table_page - 1 ) * $users_per_page ), $users_per_page );    
    $total_users = count( $table_data );
    $this->set_pagination_args( array (
      'total_items' => $total_users,
      'per_page'    => $users_per_page,
      'total_pages' => ceil( $total_users/$users_per_page )
    ) );
  }

  public function fetch_table_data() {
    global $wpdb;
    $table_name = $wpdb->prefix.'glx_contact';        
    $orderby = ( isset( $_GET['orderby'] ) ) ? esc_sql( $_GET['orderby'] ) : 'id';
    $order = ( isset( $_GET['order'] ) ) ? esc_sql( $_GET['order'] ) : 'ASC';
    $user_query = "SELECT * FROM $table_name ORDER BY $orderby $order";
    $query_results = $wpdb->get_results( $user_query, ARRAY_A  );
    return $query_results;        
  }
  public function filter_table_data( $table_data, $search_key ) {
    $filtered_table_data = array_values( array_filter( $table_data, function( $row ) use( $search_key ) {
      foreach( $row as $row_val ) {
        if( stripos( $row_val, $search_key ) !== false ) {
          return true;
        }               
      }
    } ) );
    return $filtered_table_data;
  }
}





