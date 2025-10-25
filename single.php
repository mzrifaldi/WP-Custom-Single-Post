<?php
/**
 * Single post template for Blocksy child theme
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

get_header();
?>

<main id="primary" class="site-main">
  <?php while ( have_posts() ) : the_post(); ?>

  <div class="pfg-container">
    <div class="pfg-content-wrapper">
      <!-- Main Content -->
      <article class="pfg-main-content">
        <section class="pfg-single-post">
          <!-- Post header -->
          <div class="pfg-post-header">
            <?php
            $categories = get_the_category();
            if (!empty($categories)) {
              echo '<span class="pfg-post-category">' . esc_html($categories[0]->name) . '</span>';
            }
            ?>
            <h1 class="pfg-post-title"><?php the_title(); ?></h1>
            <div class="pfg-post-meta">
              <div class="pfg-post-meta-item">
                <i class="fas fa-calendar-alt"></i>
                <span><?php the_time('j F Y'); ?></span>
              </div>
              <div class="pfg-post-meta-item">
                <i class="fas fa-user"></i>
                <span><?php the_author(); ?></span>
              </div>
              <div class="pfg-post-meta-item">
                <i class="fas fa-comments"></i>
                <span><?php comments_number('0 Komentar', '1 Komentar', '% Komentar'); ?></span>
              </div>
              <?php if(function_exists('pvc_get_post_views')): ?>
              <div class="pfg-post-meta-item">
                <i class="fas fa-eye"></i>
                <span><?php echo esc_html(pvc_get_post_views()); ?> Dibaca</span>
              </div>
              <?php endif; ?>
            </div>
          </div>
          
          <!-- Post featured image -->
          <?php if(has_post_thumbnail()): ?>
          <div class="pfg-post-featured-image">
            <?php the_post_thumbnail('full'); ?>
          </div>
          <?php endif; ?>
          
          <!-- Table of Contents -->
          <?php echo pfg_generate_toc(get_the_content()); ?>
          
          <!-- Post content -->
          <div class="pfg-post-content">
            <?php the_content(); ?>
          </div>
          
          <!-- Post tags -->
          <?php
          $tags = get_the_tags();
          if($tags): ?>
          <div class="pfg-post-tags">
            <?php foreach($tags as $tag): ?>
            <a href="<?php echo get_tag_link($tag->term_id); ?>" class="pfg-post-tag">
              <i class="fas fa-tag"></i> <?php echo esc_html($tag->name); ?>
            </a>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
          
          <!-- Post author -->
          <div class="pfg-post-author">
            <div class="pfg-post-author-image">
              <?php echo get_avatar(get_the_author_meta('ID'), 80); ?>
            </div>
            <div class="pfg-post-author-info">
              <h4>
                <i class="fas fa-user-edit"></i>
                <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" class="pfg-author-link">
                  <?php the_author(); ?>
                </a>
              </h4>
              <p><?php echo get_the_author_meta('description') ?: 'Penulis di ' . get_bloginfo('name'); ?></p>
              <div class="pfg-author-actions">
                <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" class="pfg-author-posts-btn">
                  <i class="fas fa-newspaper"></i>
                  Lihat Semua Artikel
                </a>
                <?php 
                // Count author posts
                $author_posts_count = count_user_posts(get_the_author_meta('ID'), 'post', true);
                ?>
                <span class="pfg-author-post-count">
                  <i class="fas fa-file-alt"></i>
                  <?php echo $author_posts_count; ?> Artikel
                </span>
              </div>
            </div>
          </div>
          
          <!-- Post share -->
          <div class="pfg-post-share">
            <div class="pfg-post-share-title"><i class="fas fa-share-alt"></i> Bagikan Artikel</div>
            <div class="pfg-post-share-buttons">
              <a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank" class="pfg-post-share-button pfg-post-share-facebook" aria-label="Share on Facebook"><i class="fab fa-facebook-f"></i></a>
              <a href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>" target="_blank" class="pfg-post-share-button pfg-post-share-twitter" aria-label="Share on Twitter"><i class="fab fa-twitter"></i></a>
              <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>&title=<?php the_title(); ?>" target="_blank" class="pfg-post-share-button pfg-post-share-linkedin" aria-label="Share on LinkedIn"><i class="fab fa-linkedin-in"></i></a>
              <a href="https://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>&description=<?php the_title(); ?>" target="_blank" class="pfg-post-share-button pfg-post-share-pinterest" aria-label="Share on Pinterest"><i class="fab fa-pinterest-p"></i></a>
              <a href="https://api.whatsapp.com/send?text=<?php the_title(); ?>: <?php the_permalink(); ?>" target="_blank" class="pfg-post-share-button pfg-post-share-whatsapp" aria-label="Share on WhatsApp"><i class="fab fa-whatsapp"></i></a>
            </div>
          </div>
          
          <!-- Related posts -->
          <?php
          $related_args = array(
            'post_type' => 'post',
            'posts_per_page' => 3,
            'post__not_in' => array(get_the_ID()),
            'category__in' => wp_get_post_categories(get_the_ID()),
          );
          $related_query = new WP_Query($related_args);
          
          if($related_query->have_posts()): 
          ?>
          <div class="pfg-related-posts">
            <h3 class="pfg-related-posts-title"><i class="fas fa-newspaper"></i> Artikel Terkait</h3>
            <div class="pfg-related-posts-grid">
              <?php while($related_query->have_posts()): $related_query->the_post(); ?>
              <a href="<?php the_permalink(); ?>" class="pfg-related-post-card">
                <div class="pfg-related-post-image">
                  <?php if(has_post_thumbnail()): ?>
                    <?php the_post_thumbnail('medium', array('loading' => 'lazy')); ?>
                  <?php else: ?>
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='300' viewBox='0 0 400 300'%3E%3Crect width='400' height='300' fill='%23f8f9fa'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' fill='%236c757d' font-family='Arial, sans-serif' font-size='16'%3ENo Image%3C/text%3E%3C/svg%3E" alt="<?php the_title_attribute(); ?>" loading="lazy">
                  <?php endif; ?>
                </div>
                <div class="pfg-related-post-content">
                  <h4 class="pfg-related-post-title"><?php the_title(); ?></h4>
                  <div class="pfg-related-post-date">
                    <i class="fas fa-calendar"></i> <?php the_time('j F Y'); ?>
                  </div>
                </div>
              </a>
              <?php endwhile; ?>
            </div>
          </div>
          <?php 
          endif;
          wp_reset_postdata(); 
          ?>
          
          <!-- Schema markup for SEO -->
          <script type="application/ld+json">
          {
            "@context": "https://schema.org",
            "@type": "BlogPosting",
            "mainEntityOfPage": {
              "@type": "WebPage",
              "@id": "<?php the_permalink(); ?>"
            },
            "headline": "<?php echo esc_js(get_the_title()); ?>",
            "image": "<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full')); ?>",
            "author": {
              "@type": "Person",
              "name": "<?php echo esc_js(get_the_author()); ?>"
            },
            "publisher": {
              "@type": "Organization",
              "name": "<?php echo esc_js(get_bloginfo('name')); ?>",
              "logo": {
                "@type": "ImageObject",
                "url": "<?php echo esc_url(get_site_icon_url()); ?>"
              }
            },
            "datePublished": "<?php echo get_the_date('c'); ?>",
            "dateModified": "<?php echo get_the_modified_date('c'); ?>",
            "description": "<?php echo esc_js(get_the_excerpt()); ?>"
          }
          </script>
        </section>
      </article>
      
      <!-- Sidebar -->
      <aside class="pfg-sidebar">
          <!-- Banner Iklan Sidebar -->
<div class="pfg-widget">
  <h3 class="pfg-widget-title"><i class="fas fa-ad"></i> Yuk Mulai Maklon Sekarang!</h3>
  <div style="text-align:center;">
    <a href="https://s.id/ybQGu" target="_blank" rel="nofollow sponsored">
      <img src="https://www.putrafarmayogyakarta.co.id/wp-content/uploads/2025/10/banner-iklan-pfyz-scaled.webp" alt="Iklan Sidebar" style="max-width:100%; border-radius:8px;">
    </a>
  </div>
</div>

        <!-- Search Widget -->
        <div class="pfg-widget">
          <h3 class="pfg-widget-title"><i class="fas fa-search"></i> Pencarian</h3>
          <form class="pfg-search-form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
            <input type="search" class="pfg-search-input" placeholder="Cari artikel..." value="<?php echo get_search_query(); ?>" name="s" />
            <button type="submit" class="pfg-search-button" aria-label="Search">
              <i class="fas fa-search"></i>
            </button>
          </form>
        </div>
        
        <!-- Recent Posts Widget -->
        <div class="pfg-widget">
          <h3 class="pfg-widget-title"><i class="fas fa-clock"></i> Artikel Terbaru</h3>
          <?php
          $recent_posts = wp_get_recent_posts(array(
            'numberposts' => 5,
            'post_status' => 'publish'
          ));
          if($recent_posts): ?>
          <ul class="pfg-recent-posts-list">
            <?php foreach($recent_posts as $recent): ?>
            <li>
              <a href="<?php echo get_permalink($recent['ID']); ?>" class="pfg-recent-post-link">
                <?php echo wp_trim_words($recent['post_title'], 8, '...'); ?>
              </a>
              <div class="pfg-recent-post-date">
                <i class="fas fa-calendar"></i> <?php echo date('j M Y', strtotime($recent['post_date'])); ?>
              </div>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
        </div>
        
        <!-- Categories Widget -->
        <div class="pfg-widget">
          <h3 class="pfg-widget-title"><i class="fas fa-folder"></i> Kategori</h3>
          <?php
          $categories = get_categories(array(
            'orderby' => 'count',
            'order' => 'DESC',
            'number' => 8,
            'hide_empty' => true,
          ));
          if($categories): ?>
          <ul class="pfg-categories-list">
            <?php foreach($categories as $category): ?>
            <li>
              <a href="<?php echo get_category_link($category->term_id); ?>">
                <?php echo esc_html($category->name); ?>
                <span class="pfg-category-count"><?php echo $category->count; ?></span>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
        </div>
        
        <!-- Popular Tags Widget -->
        <div class="pfg-widget">
          <h3 class="pfg-widget-title"><i class="fas fa-tags"></i> Tag Populer</h3>
          <?php
          $tags = get_tags(array(
            'orderby' => 'count',
            'order' => 'DESC',
            'number' => 15,
            'hide_empty' => true,
          ));
          if($tags): ?>
          <div class="pfg-tags-cloud">
            <?php foreach($tags as $tag): ?>
            <a href="<?php echo get_tag_link($tag->term_id); ?>" class="pfg-tag-cloud-item">
              <?php echo esc_html($tag->name); ?>
            </a>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
        
        <!-- Archives Widget -->
        <div class="pfg-widget">
          <h3 class="pfg-widget-title"><i class="fas fa-archive"></i> Arsip</h3>
          <ul class="pfg-categories-list">
            <?php
            $archives = wp_get_archives(array(
              'type' => 'monthly',
              'format' => 'option',
              'show_post_count' => true,
              'limit' => 6,
              'echo' => 0
            ));
            
            if($archives) {
              preg_match_all('/<option value="([^"]*)"[^>]*>([^<]*)<\/option>/', $archives, $matches);
              for($i = 0; $i < count($matches[1]); $i++) {
                echo '<li><a href="' . esc_url($matches[1][$i]) . '">' . esc_html($matches[2][$i]) . '</a></li>';
              }
            }
            ?>
          </ul>
        </div>
        
        <!-- Custom Widget Placeholder -->
        <div class="pfg-widget">
          <h3 class="pfg-widget-title"><i class="fas fa-info-circle"></i> Tentang Kami</h3>
          <p style="font-size: 0.9rem; line-height: 1.6; color: #6c757d;">
            <?php 
            $about_text = get_option('pfg_about_widget', '');
            if(empty($about_text)) {
              echo 'Selamat datang di ' . get_bloginfo('name') . ' CV. Putra Farma Yogyakarta. Kami menyajikan artikel-artikel berkualitas untuk menambah wawasan dan pengetahuan Anda.';
            } else {
              echo esc_html($about_text);
            }
            ?>
          </p>
        </div>
        
        <!-- Social Media Widget -->
<div class="pfg-widget">
  <h3 class="pfg-widget-title"><i class="fas fa-share-alt"></i> Ikuti Kami</h3>
  <div class="pfg-post-share-buttons" style="justify-content: flex-start; gap: 0.8rem;">
    <a href="https://www.facebook.com/putrafarma.yogyakarta/" class="pfg-post-share-button pfg-post-share-facebook" aria-label="Facebook" title="Facebook" target="_blank" rel="noopener noreferrer">
      <i class="fab fa-facebook-f"></i>
    </a>
    <a href="https://id.linkedin.com/company/putra-farma-yogyakarta" class="pfg-post-share-button pfg-post-share-linkedin" aria-label="LinkedIn" title="LinkedIn" target="_blank" rel="noopener noreferrer">
      <i class="fab fa-linkedin-in"></i>
    </a>
    <a href="https://www.instagram.com/putrafarmayogyakarta/" class="pfg-post-share-button" style="background: linear-gradient(135deg, #E4405F, #C13584);" aria-label="Instagram" title="Instagram" target="_blank" rel="noopener noreferrer">
      <i class="fab fa-instagram"></i>
    </a>
    <a href="https://www.youtube.com/channel/UCWaw-kU7YFtgY18wXZRX94A" class="pfg-post-share-button" style="background: linear-gradient(135deg, #FF0000, #CC0000);" aria-label="YouTube" title="YouTube" target="_blank" rel="noopener noreferrer">
      <i class="fab fa-youtube"></i>
    </a>
  </div>
  <p style="font-size: 0.8rem; color: #6c757d; margin-top: 1rem;">
    Ikuti media sosial kami untuk mendapatkan update artikel terbaru!
  </p>
</div>
        </div>
      </aside>
    </div>
  </div>

  <?php endwhile; ?>
</main>

<?php
get_footer();
?>