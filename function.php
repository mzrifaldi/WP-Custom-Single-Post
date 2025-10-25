/**
 * Functions.php - Add this code to your child theme functions.php
 */

/**
 * Generate Table of Contents from H2 headings
 * 
 * @param string $content Post content
 * @return string TOC HTML or empty string if no headings found
 */
function pfg_generate_toc($content) {
    // Skip if content is empty
    if (empty($content)) {
        return '';
    }
    
    // Find all H2 headings using regex
    preg_match_all('/<h2[^>]*>(.*?)<\/h2>/i', $content, $matches);
    
    // If no H2 headings found, return empty
    if (empty($matches[1])) {
        return '';
    }
    
    $toc_items = array();
    $heading_counter = 1;
    
    foreach ($matches[1] as $heading) {
        // Clean heading text - remove HTML tags
        $clean_heading = strip_tags($heading);
        
        // Create anchor ID from heading text
        $anchor_id = 'pfg-heading-' . $heading_counter;
        
        // Store for TOC list
        $toc_items[] = array(
            'text' => $clean_heading,
            'anchor' => $anchor_id
        );
        
        $heading_counter++;
    }
    
    // Generate TOC HTML
    $toc_html = '<div class="pfg-post-toc">';
    $toc_html .= '<div class="pfg-post-toc-title"><i class="fas fa-list"></i> Daftar Isi</div>';
    $toc_html .= '<ol class="pfg-post-toc-list">';
    
    foreach ($toc_items as $item) {
        $toc_html .= '<li><a href="#' . esc_attr($item['anchor']) . '">' . esc_html($item['text']) . '</a></li>';
    }
    
    $toc_html .= '</ol>';
    $toc_html .= '</div>';
    
    return $toc_html;
}

/**
 * Add anchor IDs to H2 headings in post content
 * This hook modifies the content to add clickable anchors
 */
add_filter('the_content', 'pfg_add_heading_anchors');

function pfg_add_heading_anchors($content) {
    // Only apply to single post pages
    if (!is_single() || !in_the_loop() || !is_main_query()) {
        return $content;
    }
    
    $heading_counter = 1;
    
    // Replace H2 headings with anchor IDs
    $content = preg_replace_callback(
        '/<h2([^>]*)>(.*?)<\/h2>/i',
        function($matches) use (&$heading_counter) {
            $attributes = $matches[1];
            $heading_text = $matches[2];
            $anchor_id = 'pfg-heading-' . $heading_counter;
            
            $heading_counter++;
            
            return '<h2' . $attributes . ' id="' . $anchor_id . '">' . $heading_text . '</h2>';
        },
        $content
    );
    
    return $content;
}

/**
 * Enqueue child theme styles
 */
add_action('wp_enqueue_scripts', 'pfg_enqueue_styles');

function pfg_enqueue_styles() {
    // Enqueue parent theme style
    wp_enqueue_style('blocksy-style', get_template_directory_uri() . '/style.css');
    
    // Enqueue child theme style
    wp_enqueue_style(
        'blocksy-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('blocksy-style'),
        wp_get_theme()->get('Version')
    );
    
    // Enqueue Font Awesome and Google Fonts
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), '6.0.0');
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap', array(), null);
}

/**
 * Add smooth scroll behavior for TOC links
 */
add_action('wp_footer', 'pfg_toc_smooth_scroll');

function pfg_toc_smooth_scroll() {
    if (!is_single()) {
        return;
    }
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add smooth scroll behavior to TOC links
        const tocLinks = document.querySelectorAll('.pfg-post-toc-list a[href^="#"]');
        
        tocLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    const offset = 100; // Offset for fixed headers
                    const elementPosition = targetElement.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - offset;
                    
                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
    </script>
    <?php
}
?>
