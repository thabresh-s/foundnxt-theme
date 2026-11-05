<?php
/**
 * FoundNXT Custom Nav Walker v2.2
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

        if ( $has_child ) {
            $atts['aria-haspopup'] = 'true';
            $atts['aria-expanded'] = 'false';
            if ( $depth === 0 ) {
                $atts['class'] = 'nav-parent-link';
            }
        }

        if ( $depth > 0 ) {
            $atts['tabindex'] = '-1';
        }

        $atts     = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
        $attr_str = '';
        foreach ( $atts as $attr => $val ) {
            if ( $val !== '' ) {
                $attr_str .= ' ' . $attr . '="' . esc_attr( $val ) . '"';
            }
        }

        $title        = apply_filters( 'the_title', $item->title, $item->ID );
        $item_output  = ( $args->before ?? '' );
        $item_output .= '<a' . $attr_str . '>';
        $item_output .= ( $args->link_before ?? '' );
        $item_output .= '<span class="nav-label">' . $title . '</span>';
        if ( $has_child ) {
            $item_output .= self::$chevron;
        }
        $item_output .= ( $args->link_after ?? '' );
        $item_output .= '</a>';
        $item_output .= ( $args->after ?? '' );

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}
