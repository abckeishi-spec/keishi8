<?php
/**
 * Ultra Modern Categories Section - Monochrome Professional Edition
 * カテゴリー別助成金検索セクション - モノクローム・プロフェッショナル版
 *
 * @package Grant_Insight_Perfect
 * @version 22.0-monochrome
 */

// セキュリティチェック
if (!defined('ABSPATH')) {
    exit;
}

// functions.phpとの連携確認
if (!function_exists('gi_get_acf_field_safely')) {
    require_once get_template_directory() . '/inc/4-helper-functions.php';
}

// データベースから実際のカテゴリと件数を取得
$main_categories = get_terms(array(
    'taxonomy' => 'grant_category',
    'hide_empty' => false,
    'orderby' => 'count',
    'order' => 'DESC',
    'number' => 6
));

$all_categories = get_terms(array(
    'taxonomy' => 'grant_category',
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC'
));

$prefectures = get_terms(array(
    'taxonomy' => 'grant_prefecture',
    'hide_empty' => false,
    'orderby' => 'count',
    'order' => 'DESC'
));

// カテゴリアイコンとカラー設定（モノクローム版）
$category_configs = array(
    0 => array(
        'icon' => 'fas fa-laptop-code',
        'gradient' => 'from-gray-900 to-black',
        'description' => 'IT導入・DX推進・デジタル化支援'
    ),
    1 => array(
        'icon' => 'fas fa-industry',
        'gradient' => 'from-black to-gray-900',
        'description' => 'ものづくり・製造業支援'
    ),
    2 => array(
        'icon' => 'fas fa-rocket',
        'gradient' => 'from-gray-800 to-black',
        'description' => '創業・スタートアップ支援'
    ),
    3 => array(
        'icon' => 'fas fa-store',
        'gradient' => 'from-black to-gray-800',
        'description' => '小規模事業者・商業支援'
    ),
    4 => array(
        'icon' => 'fas fa-leaf',
        'gradient' => 'from-gray-900 to-gray-700',
        'description' => '環境・省エネ・SDGs支援'
    ),
    5 => array(
        'icon' => 'fas fa-users',
        'gradient' => 'from-gray-700 to-black',
        'description' => '人材育成・雇用支援'
    )
);

$archive_base_url = get_post_type_archive_link('grant');

// 統計情報を取得（functions.phpから）
if (function_exists('gi_get_cached_stats')) {
    $stats = gi_get_cached_stats();
} else {
    $stats = array(
        'total_grants' => wp_count_posts('grant')->publish ?? 0,
        'active_grants' => 0,
        'prefecture_count' => count($prefectures)
    );
}
?>

<!-- フォント・アイコン読み込み -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Noto+Sans+JP:wght@300;400;500;700;900&display=swap" rel="stylesheet">

<!-- モノクローム・カテゴリーセクション -->
<section class="monochrome-categories" id="grant-categories">
    <!-- 背景エフェクト -->
    <div class="background-effects">
        <div class="grid-pattern"></div>
        <div class="gradient-overlay"></div>
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
    </div>

    <div class="section-container">
        <!-- セクションヘッダー -->
        <div class="section-header" data-aos="fade-up">
            <div class="header-accent"></div>
            
            <h2 class="section-title">
                <span class="title-en">CATEGORY SEARCH</span>
                <span class="title-ja">カテゴリーから探す</span>
            </h2>
            
            <p class="section-description">
                業種・目的別に最適な助成金を簡単検索
            </p>

            <!-- 統計情報 -->
            <div class="stats-row">
                <div class="stat-item">
                    <span class="stat-value" data-counter="<?php echo esc_attr($stats['total_grants']); ?>">0</span>
                    <span class="stat-label">総助成金数</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value" data-counter="<?php echo count($all_categories); ?>">0</span>
                    <span class="stat-label">カテゴリー</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value" data-counter="<?php echo esc_attr($stats['prefecture_count']); ?>">0</span>
                    <span class="stat-label">都道府県</span>
                </div>
            </div>
        </div>

        <!-- メインカテゴリーグリッド -->
        <div class="main-categories-grid">
            <?php
            if (!empty($main_categories)) :
                foreach ($main_categories as $index => $category) :
                    if ($index >= 6) break;
                    $config = $category_configs[$index] ?? array(
                        'icon' => 'fas fa-folder',
                        'gradient' => 'from-gray-800 to-black',
                        'description' => ''
                    );
                    $category_url = add_query_arg('grant_category', $category->slug, $archive_base_url);
                    
                    // カテゴリーの最新投稿を取得
                    $recent_grants = get_posts(array(
                        'post_type' => 'grant',
                        'posts_per_page' => 3,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'grant_category',
                                'field' => 'term_id',
                                'terms' => $category->term_id
                            )
                        )
                    ));
            ?>
            <div class="category-card" 
                 data-aos="fade-up" 
                 data-aos-delay="<?php echo $index * 50; ?>"
                 data-category="<?php echo esc_attr($category->slug); ?>">
                
                <div class="card-inner">
                    <!-- グラデーションボーダー -->
                    <div class="card-border"></div>
                    
                    <!-- カードコンテンツ -->
                    <div class="card-content">
                        <!-- アイコンとタイトル -->
                        <div class="card-header">
                            <div class="card-icon">
                                <i class="<?php echo esc_attr($config['icon']); ?>"></i>
                            </div>
                            <div class="card-badge">
                                <span class="badge-count"><?php echo number_format($category->count); ?></span>
                                <span class="badge-label">件</span>
                            </div>
                        </div>
                        
                        <h3 class="card-title"><?php echo esc_html($category->name); ?></h3>
                        
                        <?php if ($config['description']): ?>
                        <p class="card-description"><?php echo esc_html($config['description']); ?></p>
                        <?php endif; ?>
                        
                        <!-- 最新の助成金プレビュー -->
                        <?php if (!empty($recent_grants)): ?>
                        <div class="recent-grants">
                            <div class="recent-grants-label">最新の助成金</div>
                            <?php foreach ($recent_grants as $grant): 
                                $amount = gi_safe_get_meta($grant->ID, 'max_amount', '');
                            ?>
                            <a href="<?php echo esc_url(get_permalink($grant->ID)); ?>" class="recent-grant-item" target="_blank">
                                <span class="grant-title"><?php echo esc_html(mb_substr($grant->post_title, 0, 20)); ?>...</span>
                                <?php if ($amount): ?>
                                <span class="grant-amount"><?php echo esc_html($amount); ?></span>
                                <?php endif; ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        
                        <!-- アクションボタン -->
                        <a href="<?php echo esc_url($category_url); ?>" class="card-link">
                            <span class="link-text">詳細を見る</span>
                            <span class="link-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        </a>
                    </div>
                    
                    <!-- ホバーエフェクト -->
                    <div class="hover-effect"></div>
                </div>
            </div>
            <?php
                endforeach;
            endif;
            ?>
        </div>

        <!-- その他のカテゴリー -->
        <?php if (!empty($all_categories) && count($all_categories) > 6) :
            $other_categories = array_slice($all_categories, 6);
        ?>
        <div class="other-categories-section" data-aos="fade-up">
            <button type="button" id="toggle-categories" class="toggle-button">
                <span class="toggle-icon">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="toggle-text">その他のカテゴリーを表示</span>
                <span class="count-badge"><?php echo count($other_categories); ?></span>
            </button>

            <div id="other-categories" class="other-categories-container">
                <div class="categories-grid">
                    <?php foreach ($other_categories as $category) :
                        $category_url = add_query_arg('grant_category', $category->slug, $archive_base_url);
                    ?>
                    <a href="<?php echo esc_url($category_url); ?>" class="mini-category-card">
                        <div class="mini-card-inner">
                            <i class="fas fa-folder mini-icon"></i>
                            <span class="mini-title"><?php echo esc_html($category->name); ?></span>
                            <span class="mini-count"><?php echo $category->count; ?></span>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- 地域別検索 -->
        <div class="region-section" data-aos="fade-up">
            <div class="region-header">
                <h3 class="region-title">
                    <span class="title-en">REGIONAL SEARCH</span>
                    <span class="title-ja">都道府県を選んでください</span>
                </h3>
            </div>

            <div class="regions-container">
                <div class="japan-map-wrapper">
                    <!-- 完全な47都道府県インタラクティブマップ -->
                    <div class="map-header">
                        <span class="map-instruction">都道府県を選んでください</span>
                    </div>
                    
                    <div class="japan-regions-grid">
                        <?php
                        // 47都道府県の完全なデータ（地域ごとにグループ化）
                        $all_prefectures_by_region = array(
                            '北海道・東北' => array(
                                array('name' => '北海道', 'slug' => 'hokkaido', 'region' => 'hokkaido'),
                                array('name' => '青森', 'slug' => 'aomori', 'region' => 'tohoku'),
                                array('name' => '岩手', 'slug' => 'iwate', 'region' => 'tohoku'),
                                array('name' => '宮城', 'slug' => 'miyagi', 'region' => 'tohoku'),
                                array('name' => '秋田', 'slug' => 'akita', 'region' => 'tohoku'),
                                array('name' => '山形', 'slug' => 'yamagata', 'region' => 'tohoku'),
                                array('name' => '福島', 'slug' => 'fukushima', 'region' => 'tohoku')
                            ),
                            '関東' => array(
                                array('name' => '茨城', 'slug' => 'ibaraki', 'region' => 'kanto'),
                                array('name' => '栃木', 'slug' => 'tochigi', 'region' => 'kanto'),
                                array('name' => '群馬', 'slug' => 'gunma', 'region' => 'kanto'),
                                array('name' => '埼玉', 'slug' => 'saitama', 'region' => 'kanto'),
                                array('name' => '千葉', 'slug' => 'chiba', 'region' => 'kanto'),
                                array('name' => '東京', 'slug' => 'tokyo', 'region' => 'kanto'),
                                array('name' => '神奈川', 'slug' => 'kanagawa', 'region' => 'kanto')
                            ),
                            '中部' => array(
                                array('name' => '新潟', 'slug' => 'niigata', 'region' => 'chubu'),
                                array('name' => '富山', 'slug' => 'toyama', 'region' => 'chubu'),
                                array('name' => '石川', 'slug' => 'ishikawa', 'region' => 'chubu'),
                                array('name' => '福井', 'slug' => 'fukui', 'region' => 'chubu'),
                                array('name' => '山梨', 'slug' => 'yamanashi', 'region' => 'chubu'),
                                array('name' => '長野', 'slug' => 'nagano', 'region' => 'chubu'),
                                array('name' => '岐阜', 'slug' => 'gifu', 'region' => 'chubu'),
                                array('name' => '静岡', 'slug' => 'shizuoka', 'region' => 'chubu'),
                                array('name' => '愛知', 'slug' => 'aichi', 'region' => 'chubu')
                            ),
                            '近畿' => array(
                                array('name' => '三重', 'slug' => 'mie', 'region' => 'kinki'),
                                array('name' => '滋賀', 'slug' => 'shiga', 'region' => 'kinki'),
                                array('name' => '京都', 'slug' => 'kyoto', 'region' => 'kinki'),
                                array('name' => '大阪', 'slug' => 'osaka', 'region' => 'kinki'),
                                array('name' => '兵庫', 'slug' => 'hyogo', 'region' => 'kinki'),
                                array('name' => '奈良', 'slug' => 'nara', 'region' => 'kinki'),
                                array('name' => '和歌山', 'slug' => 'wakayama', 'region' => 'kinki')
                            ),
                            '中国・四国' => array(
                                array('name' => '鳥取', 'slug' => 'tottori', 'region' => 'chugoku'),
                                array('name' => '島根', 'slug' => 'shimane', 'region' => 'chugoku'),
                                array('name' => '岡山', 'slug' => 'okayama', 'region' => 'chugoku'),
                                array('name' => '広島', 'slug' => 'hiroshima', 'region' => 'chugoku'),
                                array('name' => '山口', 'slug' => 'yamaguchi', 'region' => 'chugoku'),
                                array('name' => '徳島', 'slug' => 'tokushima', 'region' => 'shikoku'),
                                array('name' => '香川', 'slug' => 'kagawa', 'region' => 'shikoku'),
                                array('name' => '愛媛', 'slug' => 'ehime', 'region' => 'shikoku'),
                                array('name' => '高知', 'slug' => 'kochi', 'region' => 'shikoku')
                            ),
                            '九州・沖縄' => array(
                                array('name' => '福岡', 'slug' => 'fukuoka', 'region' => 'kyushu'),
                                array('name' => '佐賀', 'slug' => 'saga', 'region' => 'kyushu'),
                                array('name' => '長崎', 'slug' => 'nagasaki', 'region' => 'kyushu'),
                                array('name' => '熊本', 'slug' => 'kumamoto', 'region' => 'kyushu'),
                                array('name' => '大分', 'slug' => 'oita', 'region' => 'kyushu'),
                                array('name' => '宮崎', 'slug' => 'miyazaki', 'region' => 'kyushu'),
                                array('name' => '鹿児島', 'slug' => 'kagoshima', 'region' => 'kyushu'),
                                array('name' => '沖縄', 'slug' => 'okinawa', 'region' => 'kyushu')
                            )
                        );
                        
                        // 実際の助成金数を取得
                        $prefecture_counts = array();
                        if (!empty($prefectures)) {
                            foreach ($prefectures as $prefecture) {
                                $prefecture_counts[$prefecture->slug] = $prefecture->count;
                            }
                        }
                        
                        // 地域ごとに表示
                        foreach ($all_prefectures_by_region as $region_name => $region_prefectures) :
                        ?>
                        <div class="region-group">
                            <div class="region-label"><?php echo esc_html($region_name); ?></div>
                            <div class="prefecture-grid">
                                <?php foreach ($region_prefectures as $pref) : 
                                    // 実際のタクソノミーからカウントを取得
                                    $actual_count = 0;
                                    $pref_term = null;
                                    
                                    // 都道府県名でマッチング
                                    foreach ($prefectures as $prefecture) {
                                        $clean_name = str_replace(array('県', '都', '府'), '', $prefecture->name);
                                        if ($clean_name === $pref['name'] || 
                                            $prefecture->name === $pref['name'] . '県' ||
                                            $prefecture->name === $pref['name'] . '都' ||
                                            $prefecture->name === $pref['name'] . '府' ||
                                            $prefecture->name === $pref['name']) {
                                            $actual_count = $prefecture->count;
                                            $pref_term = $prefecture;
                                            break;
                                        }
                                    }
                                    
                                    $pref_slug = $pref_term ? $pref_term->slug : $pref['slug'];
                                    $pref_url = add_query_arg('grant_prefecture', $pref_slug, $archive_base_url);
                                    $has_grants = $actual_count > 0;
                                ?>
                                <a href="<?php echo esc_url($pref_url); ?>" 
                                   class="prefecture-box <?php echo $has_grants ? 'has-grants' : 'no-grants'; ?>" 
                                   data-region="<?php echo esc_attr($pref['region']); ?>"
                                   data-prefecture="<?php echo esc_attr($pref_slug); ?>"
                                   data-count="<?php echo esc_attr($actual_count); ?>"
                                   title="<?php echo esc_attr($pref['name']); ?> (<?php echo $actual_count; ?>件)">
                                    <span class="pref-name"><?php echo esc_html($pref['name']); ?></span>
                                    <span class="pref-count"><?php echo $actual_count; ?></span>
                                </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- 統計サマリー -->
                    <div class="map-statistics">
                        <div class="stat-item">
                            <span class="stat-label">対象地域</span>
                            <span class="stat-value">47</span>
                            <span class="stat-unit">都道府県</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">総助成金数</span>
                            <span class="stat-value"><?php 
                                $total_grants = 0;
                                foreach ($prefectures as $pref) {
                                    $total_grants += $pref->count;
                                }
                                echo $total_grants;
                            ?></span>
                            <span class="stat-unit">件</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">平均</span>
                            <span class="stat-value"><?php 
                                $active_prefectures = count($prefectures) > 0 ? count($prefectures) : 1;
                                echo round($total_grants / $active_prefectures); 
                            ?></span>
                            <span class="stat-unit">件/県</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="cta-section" data-aos="fade-up">
            <div class="cta-content">
                <h3 class="cta-title">すべての助成金を探す</h3>
                <p class="cta-description">条件を絞り込んで、あなたに最適な助成金を見つけましょう</p>
                <a href="<?php echo esc_url($archive_base_url); ?>" class="cta-button">
                    <span class="button-text">助成金を検索</span>
                    <span class="button-icon">
                        <i class="fas fa-search"></i>
                    </span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- モノクローム・スタイル -->
<style>
/* ベース設定 */
.monochrome-categories {
    position: relative;
    padding: 100px 0;
    background: #ffffff;
    overflow: hidden;
    font-family: 'Inter', 'Noto Sans JP', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* 背景エフェクト */
.background-effects {
    position: absolute;
    inset: 0;
    pointer-events: none;
}

.grid-pattern {
    position: absolute;
    inset: 0;
    background-image: 
        linear-gradient(rgba(0, 0, 0, 0.02) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0, 0, 0, 0.02) 1px, transparent 1px);
    background-size: 50px 50px;
}

.gradient-overlay {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 50% 50%, transparent 0%, rgba(255, 255, 255, 0.8) 100%);
}

.floating-shapes {
    position: absolute;
    inset: 0;
}

.shape {
    position: absolute;
    border-radius: 50%;
    filter: blur(100px);
    opacity: 0.05;
}

.shape-1 {
    width: 600px;
    height: 600px;
    background: #000000;
    top: -300px;
    right: -200px;
    animation: float 20s ease-in-out infinite;
}

.shape-2 {
    width: 400px;
    height: 400px;
    background: #333333;
    bottom: -200px;
    left: -100px;
    animation: float 25s ease-in-out infinite reverse;
}

.shape-3 {
    width: 300px;
    height: 300px;
    background: #666666;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    animation: pulse 15s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-30px) rotate(180deg); }
}

@keyframes pulse {
    0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.05; }
    50% { transform: translate(-50%, -50%) scale(1.2); opacity: 0.1; }
}

/* コンテナ */
.section-container {
    position: relative;
    z-index: 1;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* セクションヘッダー */
.section-header {
    text-align: center;
    margin-bottom: 80px;
    position: relative;
}

.header-accent {
    width: 60px;
    height: 4px;
    background: #000000;
    margin: 0 auto 40px;
    position: relative;
    overflow: hidden;
}

.header-accent::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.8), transparent);
    animation: shine 3s ease-in-out infinite;
}

@keyframes shine {
    0% { left: -100%; }
    100% { left: 200%; }
}

.section-title {
    margin-bottom: 20px;
}

.title-en {
    display: block;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 0.3em;
    text-transform: uppercase;
    color: #999999;
    margin-bottom: 12px;
}

.title-ja {
    display: block;
    font-size: clamp(36px, 5vw, 48px);
    font-weight: 900;
    color: #000000;
    line-height: 1.2;
    letter-spacing: 0.02em;
}

.section-description {
    font-size: 18px;
    color: #666666;
    margin-bottom: 40px;
    font-weight: 400;
}

/* 統計情報 */
.stats-row {
    display: flex;
    justify-content: center;
    gap: 60px;
    padding: 40px;
    background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
    border-radius: 20px;
    border: 1px solid #e0e0e0;
}

.stat-item {
    text-align: center;
}

.stat-value {
    display: block;
    font-size: 42px;
    font-weight: 900;
    color: #000000;
    margin-bottom: 8px;
    font-feature-settings: 'tnum';
    position: relative;
}

.stat-value::after {
    content: '+';
    position: absolute;
    right: -15px;
    top: 0;
    font-size: 24px;
    font-weight: 400;
    color: #999999;
}

.stat-label {
    font-size: 13px;
    color: #999999;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    font-weight: 600;
}

/* メインカテゴリーグリッド */
.main-categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 30px;
    margin-bottom: 60px;
}

/* カテゴリーカード */
.category-card {
    position: relative;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.category-card:hover {
    transform: translateY(-5px);
}

.card-inner {
    position: relative;
    background: #ffffff;
    border-radius: 20px;
    overflow: hidden;
    height: 100%;
}

.card-border {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #000000, #333333, #000000);
    padding: 2px;
    border-radius: 20px;
    -webkit-mask: 
        linear-gradient(#fff 0 0) content-box, 
        linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
}

.card-content {
    position: relative;
    padding: 35px;
    background: #ffffff;
    border-radius: 18px;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 25px;
}

.card-icon {
    width: 56px;
    height: 56px;
    background: #000000;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 24px;
    transition: all 0.3s ease;
}

.category-card:hover .card-icon {
    background: #333333;
    transform: rotate(5deg);
}

.card-badge {
    text-align: right;
}

.badge-count {
    font-size: 28px;
    font-weight: 900;
    color: #ffffff;
    display: block;
    background: #000000;
    padding: 8px 12px;
    border-radius: 8px;
}

.badge-label {
    font-size: 12px;
    color: #000000;
    font-weight: 600;
    margin-top: 4px;
    display: block;
}

.card-title {
    font-size: 22px;
    font-weight: 800;
    color: #000000;
    margin-bottom: 12px;
    line-height: 1.3;
}

.card-description {
    font-size: 14px;
    color: #666666;
    line-height: 1.6;
    margin-bottom: 25px;
}

/* 最新の助成金 */
.recent-grants {
    margin: 20px 0;
    padding: 20px;
    background: #fafafa;
    border-radius: 12px;
    border: 1px solid #e0e0e0;
}

.recent-grants-label {
    font-size: 11px;
    font-weight: 700;
    color: #999999;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 12px;
}

.recent-grant-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #e0e0e0;
    text-decoration: none;
    transition: all 0.2s ease;
}

a.recent-grant-item:hover {
    background: rgba(0, 0, 0, 0.02);
    padding-left: 8px;
    margin-left: -8px;
    padding-right: 8px;
    margin-right: -8px;
}

.recent-grant-item:last-child {
    border-bottom: none;
}

.grant-title {
    font-size: 13px;
    color: #333333;
    flex: 1;
}

.grant-amount {
    font-size: 13px;
    font-weight: 700;
    color: #000000;
}

.card-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 24px;
    background: #000000;
    color: #ffffff;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    font-size: 15px;
    transition: all 0.3s ease;
    margin-top: auto;
}

.card-link:hover {
    background: #ffffff;
    color: #000000;
    box-shadow: inset 0 0 0 2px #000000;
}

.link-arrow {
    transition: transform 0.3s ease;
}

.card-link:hover .link-arrow {
    transform: translateX(5px);
}

/* ホバーエフェクト */
.hover-effect {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, transparent, rgba(0, 0, 0, 0.05));
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.category-card:hover .hover-effect {
    opacity: 1;
}

/* その他のカテゴリー */
.other-categories-section {
    margin-bottom: 80px;
}

.toggle-button {
    display: flex;
    align-items: center;
    gap: 16px;
    margin: 0 auto 40px;
    padding: 18px 32px;
    background: #ffffff;
    border: 2px solid #000000;
    border-radius: 999px;
    font-size: 15px;
    font-weight: 700;
    color: #000000;
    cursor: pointer;
    transition: all 0.3s ease;
}

.toggle-button:hover {
    background: #000000;
    color: #ffffff;
}

.toggle-icon {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease;
}

.toggle-button.active .toggle-icon {
    transform: rotate(45deg);
}

.count-badge {
    padding: 4px 12px;
    background: #000000;
    color: #ffffff;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
}

.toggle-button:hover .count-badge {
    background: #ffffff;
    color: #000000;
}

.other-categories-container {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.5s ease;
}

.other-categories-container.show {
    max-height: 2000px;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 16px;
    padding: 40px;
    background: #fafafa;
    border-radius: 20px;
    border: 2px solid #000000;
}

/* ミニカテゴリーカード */
.mini-category-card {
    display: block;
    text-decoration: none;
    transition: all 0.3s ease;
}

.mini-card-inner {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.mini-category-card:hover .mini-card-inner {
    background: #000000;
    border-color: #000000;
}

.mini-icon {
    font-size: 18px;
    color: #666666;
    transition: color 0.3s ease;
}

.mini-category-card:hover .mini-icon {
    color: #ffffff;
}

.mini-title {
    flex: 1;
    font-size: 14px;
    font-weight: 600;
    color: #000000;
    transition: color 0.3s ease;
}

.mini-category-card:hover .mini-title {
    color: #ffffff;
}

.mini-count {
    padding: 4px 8px;
    background: #f0f0f0;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    color: #666666;
    transition: all 0.3s ease;
}

.mini-category-card:hover .mini-count {
    background: #ffffff;
    color: #000000;
}

/* 地域セクション */
.region-section {
    margin-bottom: 80px;
}

.region-header {
    text-align: center;
    margin-bottom: 50px;
}

.region-title {
    margin-bottom: 40px;
}

.regions-container {
    max-width: 1000px;
    margin: 0 auto;
}

/* 日本地図ラッパー */
.japan-map-wrapper {
    background: #000000;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

.map-header {
    text-align: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #333333;
}

.map-instruction {
    font-size: 18px;
    font-weight: 700;
    color: #ffffff;
    letter-spacing: 0.05em;
}

/* 地域グリッド */
.japan-regions-grid {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.region-group {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.region-label {
    font-size: 14px;
    font-weight: 700;
    color: #ffffff;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.prefecture-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    gap: 10px;
}

/* 都道府県ボックス */
.prefecture-box {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 12px 8px;
    background: #ffffff;
    border: 2px solid #333333;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
    min-height: 60px;
}

.prefecture-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.2);
    background: #333333;
    border-color: #ffffff;
}

.prefecture-box.has-grants {
    background: #1a1a1a;
    border-color: #4CAF50;
}

.prefecture-box.has-grants:hover {
    background: #4CAF50;
    border-color: #4CAF50;
}

.prefecture-box.no-grants {
    opacity: 0.5;
    background: #2a2a2a;
    border-color: #555555;
}

.pref-name {
    font-size: 13px;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 4px;
    transition: color 0.3s ease;
}

.prefecture-box:hover .pref-name {
    color: #ffffff;
}

.prefecture-box.has-grants .pref-name {
    color: #4CAF50;
}

.prefecture-box.has-grants:hover .pref-name {
    color: #ffffff;
}

.pref-count {
    font-size: 11px;
    font-weight: 600;
    color: #999999;
    background: rgba(255, 255, 255, 0.1);
    padding: 2px 6px;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.prefecture-box:hover .pref-count {
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff;
}

.prefecture-box.has-grants .pref-count {
    background: rgba(76, 175, 80, 0.2);
    color: #81C784;
}

.prefecture-box.has-grants:hover .pref-count {
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff;
}

/* 統計サマリー */
.map-statistics {
    display: flex;
    justify-content: space-around;
    margin-top: 30px;
    padding-top: 25px;
    border-top: 2px solid #333333;
}

.map-statistics .stat-item {
    text-align: center;
    flex: 1;
}

.map-statistics .stat-label {
    display: block;
    font-size: 12px;
    color: #999999;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 8px;
}

.map-statistics .stat-value {
    display: block;
    font-size: 32px;
    font-weight: 900;
    color: #4CAF50;
    line-height: 1;
}

.map-statistics .stat-unit {
    display: block;
    font-size: 11px;
    color: #666666;
    margin-top: 4px;
}

/* CTA */
.cta-section {
    text-align: center;
    padding: 80px 40px;
    background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
    border-radius: 30px;
    position: relative;
    overflow: hidden;
}

.cta-section::before {
    content: '';
    position: absolute;
    inset: 0;
    background: 
        repeating-linear-gradient(
            45deg,
            transparent,
            transparent 10px,
            rgba(255, 255, 255, 0.02) 10px,
            rgba(255, 255, 255, 0.02) 20px
        );
}

.cta-content {
    position: relative;
    z-index: 1;
}

.cta-title {
    font-size: 36px;
    font-weight: 900;
    color: #ffffff;
    margin-bottom: 16px;
}

.cta-description {
    font-size: 16px;
    color: #cccccc;
    margin-bottom: 32px;
}

.cta-button {
    display: inline-flex;
    align-items: center;
    gap: 16px;
    padding: 20px 40px;
    background: #ffffff;
    color: #000000;
    border-radius: 999px;
    font-size: 16px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.cta-button::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(0, 0, 0, 0.1);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s ease, height 0.6s ease;
}

.cta-button:hover::before {
    width: 300px;
    height: 300px;
}

.button-text,
.button-icon {
    position: relative;
    z-index: 1;
}

.button-icon {
    transition: transform 0.3s ease;
}

.cta-button:hover .button-icon {
    transform: rotate(90deg);
}

/* アニメーション */
[data-aos] {
    opacity: 0;
    transition: opacity 0.6s ease, transform 0.6s ease;
}

[data-aos="fade-up"] {
    transform: translateY(30px);
}

[data-aos].aos-animate {
    opacity: 1;
    transform: translateY(0);
}

/* レスポンシブ */
@media (max-width: 1024px) {
    .main-categories-grid {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }
    
    .regions-container {
        padding: 0 15px;
    }
    
    .japan-map-wrapper {
        padding: 20px;
    }
    
    .prefecture-grid {
        grid-template-columns: repeat(auto-fill, minmax(70px, 1fr));
        gap: 8px;
    }
    
    .prefecture-box {
        min-height: 50px;
        padding: 10px 6px;
    }
    
    .pref-name {
        font-size: 12px;
    }
    
    .pref-count {
        font-size: 10px;
    }
}

@media (max-width: 640px) {
    .monochrome-categories {
        padding: 60px 0;
    }
    
    .stats-row {
        flex-direction: column;
        gap: 30px;
    }
    
    .main-categories-grid {
        grid-template-columns: 1fr;
    }
    
    .card-content {
        padding: 25px;
    }
    
    .categories-grid {
        grid-template-columns: 1fr;
    }
    
    .japan-map-wrapper {
        padding: 15px;
        border-radius: 15px;
    }
    
    .map-header {
        margin-bottom: 20px;
        padding-bottom: 15px;
    }
    
    .map-instruction {
        font-size: 16px;
    }
    
    .region-group {
        padding: 15px;
        margin-bottom: 10px;
    }
    
    .region-label {
        font-size: 12px;
        margin-bottom: 10px;
    }
    
    .prefecture-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 6px;
    }
    
    .prefecture-box {
        min-height: 45px;
        padding: 8px 4px;
    }
    
    .pref-name {
        font-size: 11px;
    }
    
    .pref-count {
        font-size: 9px;
        padding: 1px 4px;
    }
    
    .map-statistics {
        flex-direction: column;
        gap: 15px;
        padding-top: 20px;
        margin-top: 20px;
    }
    
    .map-statistics .stat-value {
        font-size: 24px;
    }
    
    .cta-section {
        padding: 60px 20px;
    }
    
    .cta-title {
        font-size: 28px;
    }
}
/* 波紋エフェクト */
.ripple-effect {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    transform: scale(0);
    animation: ripple 0.6s ease-out;
    pointer-events: none;
}

@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

/* 検索ボックス */
.prefecture-search-box {
    position: relative;
    margin-bottom: 20px;
}

.prefecture-search-input {
    width: 100%;
    padding: 12px 40px 12px 16px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    color: #ffffff;
    font-size: 14px;
    transition: all 0.3s ease;
}

.prefecture-search-input::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

.prefecture-search-input:focus {
    outline: none;
    background: rgba(255, 255, 255, 0.15);
    border-color: #4CAF50;
}

.search-icon {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255, 255, 255, 0.5);
    font-size: 16px;
}

/* アクセシビリティ */
.prefecture-box:focus {
    outline: 2px solid #4CAF50;
    outline-offset: 2px;
}

/* ダークモード対応 */
@media (prefers-color-scheme: light) {
    .japan-map-wrapper {
        background: #ffffff;
        border: 2px solid #000000;
    }
    
    .map-instruction {
        color: #000000;
    }
    
    .region-label {
        color: #000000;
    }
    
    .prefecture-box {
        background: #000000;
        border-color: #ffffff;
    }
    
    .pref-name {
        color: #ffffff;
    }
}
</style>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // カウンターアニメーション
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px'
    };
    
    const counterObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.getAttribute('data-counter'));
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    counter.textContent = Math.floor(current).toLocaleString();
                }, 30);
                counterObserver.unobserve(counter);
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('[data-counter]').forEach(counter => {
        counterObserver.observe(counter);
    });
    
    // AOS風アニメーション
    const aosElements = document.querySelectorAll('[data-aos]');
    const aosObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const delay = entry.target.getAttribute('data-aos-delay') || 0;
                setTimeout(() => {
                    entry.target.classList.add('aos-animate');
                }, delay);
                aosObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    aosElements.forEach(element => {
        aosObserver.observe(element);
    });
    
    // カテゴリー開閉
    const toggleCategories = document.getElementById('toggle-categories');
    const otherCategories = document.getElementById('other-categories');
    
    if (toggleCategories && otherCategories) {
        toggleCategories.addEventListener('click', function() {
            const isOpen = otherCategories.classList.contains('show');
            
            if (isOpen) {
                otherCategories.classList.remove('show');
                this.classList.remove('active');
                this.querySelector('.toggle-text').textContent = 'その他のカテゴリーを表示';
                this.querySelector('.toggle-icon i').className = 'fas fa-plus';
            } else {
                otherCategories.classList.add('show');
                this.classList.add('active');
                this.querySelector('.toggle-text').textContent = 'カテゴリーを閉じる';
                this.querySelector('.toggle-icon i').className = 'fas fa-minus';
                
                // スムーズスクロール
                setTimeout(() => {
                    otherCategories.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'nearest' 
                    });
                }, 100);
            }
        });
    }
    
    // カードホバーエフェクト
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('mouseenter', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            this.style.setProperty('--mouse-x', x + 'px');
            this.style.setProperty('--mouse-y', y + 'px');
        });
    });
    
    // 47都道府県インタラクション
    const prefectureBoxes = document.querySelectorAll('.prefecture-box');
    const regionGroups = document.querySelectorAll('.region-group');
    
    // 都道府県ボックスのホバー効果
    prefectureBoxes.forEach(box => {
        box.addEventListener('mouseenter', function() {
            const region = this.getAttribute('data-region');
            const prefecture = this.getAttribute('data-prefecture');
            
            // 同じ地域の都道府県をハイライト
            document.querySelectorAll(`.prefecture-box[data-region="${region}"]`).forEach(pBox => {
                if (pBox !== this) {
                    pBox.style.opacity = '0.7';
                }
            });
            
            // ツールチップ風の情報表示
            const count = this.querySelector('.pref-count').textContent;
            const name = this.querySelector('.pref-name').textContent;
            if (parseInt(count) > 0) {
                this.style.transform = 'translateY(-4px) scale(1.05)';
            }
        });
        
        box.addEventListener('mouseleave', function() {
            // リセット
            prefectureBoxes.forEach(pBox => {
                pBox.style.opacity = '';
                pBox.style.transform = '';
            });
        });
        
        // クリック時のアニメーション
        box.addEventListener('click', function(e) {
            // 波紋エフェクト
            const ripple = document.createElement('span');
            ripple.classList.add('ripple-effect');
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
    
    // 地域グループのホバー効果
    regionGroups.forEach(group => {
        group.addEventListener('mouseenter', function() {
            this.style.background = 'rgba(255, 255, 255, 0.08)';
            this.style.borderColor = 'rgba(255, 255, 255, 0.2)';
        });
        
        group.addEventListener('mouseleave', function() {
            this.style.background = '';
            this.style.borderColor = '';
        });
    });
    
    // 検索機能（オプション）
    const createSearchFilter = () => {
        const mapWrapper = document.querySelector('.japan-map-wrapper');
        if (!mapWrapper) return;
        
        const searchBox = document.createElement('div');
        searchBox.className = 'prefecture-search-box';
        searchBox.innerHTML = `
            <input type="text" placeholder="都道府県を検索..." class="prefecture-search-input">
            <i class="fas fa-search search-icon"></i>
        `;
        
        mapWrapper.insertBefore(searchBox, mapWrapper.firstChild);
        
        const searchInput = searchBox.querySelector('.prefecture-search-input');
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            
            prefectureBoxes.forEach(box => {
                const prefName = box.querySelector('.pref-name').textContent.toLowerCase();
                if (prefName.includes(searchTerm) || searchTerm === '') {
                    box.style.display = '';
                    box.style.opacity = '';
                } else {
                    box.style.display = 'none';
                }
            });
            
            // 空の地域グループを非表示
            regionGroups.forEach(group => {
                const visibleBoxes = group.querySelectorAll('.prefecture-box:not([style*="display: none"])');
                if (visibleBoxes.length === 0) {
                    group.style.display = 'none';
                } else {
                    group.style.display = '';
                }
            });
        });
    };
    
    // 検索機能を初期化
    // createSearchFilter();
    
    // パフォーマンス最適化：Intersection Observerでの遅延読み込み
    const lazyLoadObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // ここで追加のコンテンツを読み込み
                const element = entry.target;
                element.classList.add('loaded');
                lazyLoadObserver.unobserve(element);
            }
        });
    }, {
        rootMargin: '100px'
    });
    
    // functions.phpとの連携：AJAX呼び出し例
    function loadCategoryGrants(categorySlug) {
        if (typeof gi_ajax === 'undefined') {
            console.warn('gi_ajax object not found');
            return;
        }
        
        fetch(gi_ajax.url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'gi_load_grants',
                nonce: gi_ajax.nonce,
                categories: JSON.stringify([categorySlug]),
                view: 'grid'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Grants loaded:', data.data);
                // ここで取得したデータを表示
            }
        })
        .catch(error => {
            console.error('Error loading grants:', error);
        });
    }
    
    // カテゴリーカードクリック時のプレビュー機能
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.closest('.card-link')) {
                return; // リンククリック時は通常の動作
            }
            
            const category = this.getAttribute('data-category');
            if (category && typeof loadCategoryGrants === 'function') {
                loadCategoryGrants(category);
            }
        });
    });
    
    console.log('Monochrome Categories Section initialized successfully');
});
</script>

<?php
// デバッグ情報（開発環境のみ）
if (defined('WP_DEBUG') && WP_DEBUG) {
    echo '<!-- Categories Section Debug Info -->';
    echo '<!-- Total Categories: ' . count($all_categories) . ' -->';
    echo '<!-- Total Prefectures: ' . count($prefectures) . ' -->';
    echo '<!-- Theme Version: ' . GI_THEME_VERSION . ' -->';
}
?>
