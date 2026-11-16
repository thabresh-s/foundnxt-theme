<?php
/**
 * FoundNXT Custom Nav Walker v2.3
 * Modern chevron dropdown, keyboard navigation, ARIA-complete
 *
 * @package FoundNXT
 */

defined('ABSPATH') || exit;

class FNX_Nav_Walker extends Walker_Nav_Menu {

    private static $chevron = '<svg class="nav-chevron" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>';

    public function start_lvl( &$output, $depth = 0, $args = null ) {
        $indent  = str_repeat( "\t", $depth );
        $class   = 'sub-menu depth-' . $depth;
        $output .= "\n{$indent}<ul class=\"{$class}\" role=\"menu\">\n";
    }

    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $classes   = empty( $item->classes ) ? [] : (array) $item->classes;
        $has_child = in_array( 'menu-item-has-children', $classes, true );

        if ( $has_child ) {
            $classes[] = 'has-dropdown';
        }

        $class_str = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $li_id     = 'menu-item-' . $item->ID;

        $output .= '<li id="' . esc_attr( $li_id ) . '" class="' . esc_attr( $class_str ) . '" role="none">';

        $atts = [
            'href'   => ! empty( $item->url )        ? $item->url        : '#',
            'title'  => ! empty( $item->attr_title ) ? $item->attr_title : '',
            'target' => ! empty( $item->target )     ? $item->target     : '',
            'rel'    => ! empty( $item->xfn )        ? $item->xfn        : '',
            'role'   => 'menuitem',
        ];

        // Detect if this is mobile menu
        $is_mobile = ( ! empty( $args->theme_location ) && $args->theme_location === 'mobile' )
            || ( ! empty( $args->menu_class ) && strpos( $args->menu_class, 'mobile' ) !== false );

        $link_classes = [];

        if ( $is_mobile ) {
            $link_classes[] = ( $depth === 0 ) ? 'mobile-menu-link' : 'mobile-sublink';
        }

        if ( $has_child ) {
            $atts['aria-haspopup'] = 'true';
            $atts['aria-expanded'] = 'false';
            if ( $depth === 0 ) {
                $link_classes[] = 'nav-parent-link';
            }
        }

        if ( empty( $item->url ) || $item->url === '#' ) {
            $atts['role'] = 'button';
        }

        if ( ! empty( $link_classes ) ) {
            $atts['class'] = implode( ' ', $link_classes );
        }

        if ( $depth > 0 && ! $is_mobile ) {
            $atts['tabindex'] = '-1';
        }

        $atts     = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
        $attr_str = '';
        foreach ( $atts as $attr => $val ) {
            if ( $val !== '' ) {
                $attr_str .= ' ' . $attr . '="' . esc_attr( $val ) . '"';
            }
        }

        $title = apply_filters( 'the_title', $item->title, $item->ID );

        // Category color dot if sub-item is a category
        $cat_dot = '';
        if ( $depth > 0 ) {
            $cat_slug = '';
            if ( $item->object === 'category' && ! empty( $item->object_id ) ) {
                $term = get_category( $item->object_id );
                if ( $term && ! is_wp_error( $term ) ) {
                    $cat_slug = $term->slug;
                }
            }
            if ( ! empty( $cat_slug ) && function_exists( 'fnx_get_primary_categories' ) ) {
                foreach ( fnx_get_primary_categories() as $pcat ) {
                    if ( $pcat['slug'] === $cat_slug ) {
                        $cat_dot = '<span class="cat-menu-dot" style="background:' . esc_attr( $pcat['color'] ) . '"></span>';
                        break;
                    }
                }
            }
        }

        $item_output  = ( $args->before ?? '' );
        $item_output .= '<a' . $attr_str . '>';
        $item_output .= ( $args->link_before ?? '' );
        $item_output .= $cat_dot;
        $item_output .= '<span class="nav-label">' . $title . '</span>';
        if ( $has_child && ! $is_mobile ) {
            $item_output .= self::$chevron;
        }
        $item_output .= ( $args->link_after ?? '' );
        $item_output .= '</a>';
        $item_output .= ( $args->after ?? '' );

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

/**
 * Fallback menu for desktop primary navigation when no menu is assigned in WordPress admin.
 */
function fnx_primary_nav_fallback() {
    ?>
    <ul class="nav-menu" id="primary-menu">
      <li class="menu-item"><a href="<?php echo esc_url( home_url( '/articles/' ) ); ?>"><span class="nav-label"><?php _e( 'Articles', 'foundnxt' ); ?></span></a></li>
      <li class="menu-item menu-item-has-children has-dropdown">
        <a href="#" class="nav-parent-link" aria-haspopup="true" aria-expanded="false" role="button">
          <span class="nav-label"><?php _e( 'Categories', 'foundnxt' ); ?></span>
          <svg class="nav-chevron" width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
        <ul class="sub-menu depth-0" role="menu">
          <?php if ( function_exists( 'fnx_get_primary_categories' ) ) : ?>
            <?php foreach ( fnx_get_primary_categories() as $pcat ) : ?>
              <li class="menu-item" role="none">
                <a href="<?php echo esc_url( $pcat['url'] ); ?>" role="menuitem">
                  <span class="cat-menu-dot" style="background:<?php echo esc_attr( $pcat['color'] ); ?>"></span>
                  <span class="nav-label"><?php echo esc_html( $pcat['name'] ); ?></span>
                </a>
              </li>
            <?php endforeach; ?>
          <?php endif; ?>
        </ul>
      </li>
      <li class="menu-item"><a href="<?php echo esc_url( home_url( '/tools/' ) ); ?>"><span class="nav-label"><?php _e( 'Tools', 'foundnxt' ); ?></span></a></li>
      <li class="menu-item"><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><span class="nav-label"><?php _e( 'Services', 'foundnxt' ); ?></span></a></li>
      <li class="menu-item"><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><span class="nav-label"><?php _e( 'About', 'foundnxt' ); ?></span></a></li>
    </ul>
    <?php
}

/**
 * Fallback menu for mobile navigation when no menu is assigned in WordPress admin.
 */
function fnx_mobile_nav_fallback() {
    ?>
    <ul class="mobile-menu mobile-menu-list" id="mobile-menu">
      <li class="menu-item"><a href="<?php echo esc_url( home_url( '/articles/' ) ); ?>" class="mobile-menu-link"><span class="nav-label"><?php _e( 'Articles', 'foundnxt' ); ?></span></a></li>
      <li class="menu-item menu-item-has-children has-dropdown">
        <a href="#" class="mobile-menu-link nav-parent-link" aria-haspopup="true" aria-expanded="false" role="button">
          <span class="nav-label"><?php _e( 'Categories', 'foundnxt' ); ?></span>
        </a>
        <ul class="sub-menu depth-0" role="menu">
          <?php if ( function_exists( 'fnx_get_primary_categories' ) ) : ?>
            <?php foreach ( fnx_get_primary_categories() as $pcat ) : ?>
              <li class="menu-item" role="none">
                <a href="<?php echo esc_url( $pcat['url'] ); ?>" class="mobile-sublink" role="menuitem">
                  <span class="cat-menu-dot" style="background:<?php echo esc_attr( $pcat['color'] ); ?>"></span>
                  <span class="nav-label"><?php echo esc_html( $pcat['name'] ); ?></span>
                </a>
              </li>
            <?php endforeach; ?>
          <?php endif; ?>
        </ul>
      </li>
      <li class="menu-item"><a href="<?php echo esc_url( home_url( '/tools/' ) ); ?>" class="mobile-menu-link"><span class="nav-label"><?php _e( 'Tools', 'foundnxt' ); ?></span></a></li>
      <li class="menu-item"><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>" class="mobile-menu-link"><span class="nav-label"><?php _e( 'Services', 'foundnxt' ); ?></span></a></li>
      <li class="menu-item"><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="mobile-menu-link"><span class="nav-label"><?php _e( 'About', 'foundnxt' ); ?></span></a></li>
    </ul>
    <?php
}
