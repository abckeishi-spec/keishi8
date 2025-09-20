<?php
/**
 * Simple Japan Map with Grid Layout
 * シンプルな日本地図グリッドレイアウト
 * 
 * @package Grant_Insight
 * @version 3.0.0
 */

// セキュリティチェック
if (!defined('ABSPATH')) {
    exit;
}

// 助成金データを都道府県別に取得
$prefectures_data = get_terms(array(
    'taxonomy' => 'grant_prefecture',
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC'
));

// 都道府県データをコード順に整理
$prefecture_counts = array();
foreach ($prefectures_data as $pref) {
    $clean_name = str_replace(array('県', '都', '府'), '', $pref->name);
    $prefecture_counts[$clean_name] = array(
        'count' => $pref->count,
        'slug' => $pref->slug,
        'url' => add_query_arg('grant_prefecture', $pref->slug, get_post_type_archive_link('grant'))
    );
}

// 47都道府県の完全リスト（地域別）
$regions = array(
    '北海道' => array(
        array('code' => '01', 'name' => '北海道', 'x' => 85, 'y' => 5)
    ),
    '東北' => array(
        array('code' => '02', 'name' => '青森', 'x' => 80, 'y' => 15),
        array('code' => '03', 'name' => '岩手', 'x' => 85, 'y' => 20),
        array('code' => '04', 'name' => '宮城', 'x' => 85, 'y' => 25),
        array('code' => '05', 'name' => '秋田', 'x' => 80, 'y' => 20),
        array('code' => '06', 'name' => '山形', 'x' => 80, 'y' => 25),
        array('code' => '07', 'name' => '福島', 'x' => 83, 'y' => 30)
    ),
    '関東' => array(
        array('code' => '08', 'name' => '茨城', 'x' => 85, 'y' => 35),
        array('code' => '09', 'name' => '栃木', 'x' => 82, 'y' => 35),
        array('code' => '10', 'name' => '群馬', 'x' => 78, 'y' => 35),
        array('code' => '11', 'name' => '埼玉', 'x' => 80, 'y' => 38),
        array('code' => '12', 'name' => '千葉', 'x' => 85, 'y' => 40),
        array('code' => '13', 'name' => '東京', 'x' => 82, 'y' => 40),
        array('code' => '14', 'name' => '神奈川', 'x' => 82, 'y' => 43)
    ),
    '中部' => array(
        array('code' => '15', 'name' => '新潟', 'x' => 77, 'y' => 28),
        array('code' => '16', 'name' => '富山', 'x' => 73, 'y' => 33),
        array('code' => '17', 'name' => '石川', 'x' => 70, 'y' => 33),
        array('code' => '18', 'name' => '福井', 'x' => 68, 'y' => 36),
        array('code' => '19', 'name' => '山梨', 'x' => 78, 'y' => 40),
        array('code' => '20', 'name' => '長野', 'x' => 75, 'y' => 38),
        array('code' => '21', 'name' => '岐阜', 'x' => 72, 'y' => 38),
        array('code' => '22', 'name' => '静岡', 'x' => 78, 'y' => 43),
        array('code' => '23', 'name' => '愛知', 'x' => 73, 'y' => 43)
    ),
    '近畿' => array(
        array('code' => '24', 'name' => '三重', 'x' => 71, 'y' => 46),
        array('code' => '25', 'name' => '滋賀', 'x' => 68, 'y' => 41),
        array('code' => '26', 'name' => '京都', 'x' => 66, 'y' => 40),
        array('code' => '27', 'name' => '大阪', 'x' => 66, 'y' => 45),
        array('code' => '28', 'name' => '兵庫', 'x' => 63, 'y' => 43),
        array('code' => '29', 'name' => '奈良', 'x' => 68, 'y' => 45),
        array('code' => '30', 'name' => '和歌山', 'x' => 66, 'y' => 48)
    ),
    '中国' => array(
        array('code' => '31', 'name' => '鳥取', 'x' => 58, 'y' => 40),
        array('code' => '32', 'name' => '島根', 'x' => 55, 'y' => 40),
        array('code' => '33', 'name' => '岡山', 'x' => 60, 'y' => 43),
        array('code' => '34', 'name' => '広島', 'x' => 57, 'y' => 43),
        array('code' => '35', 'name' => '山口', 'x' => 52, 'y' => 43)
    ),
    '四国' => array(
        array('code' => '36', 'name' => '徳島', 'x' => 62, 'y' => 48),
        array('code' => '37', 'name' => '香川', 'x' => 60, 'y' => 46),
        array('code' => '38', 'name' => '愛媛', 'x' => 56, 'y' => 48),
        array('code' => '39', 'name' => '高知', 'x' => 58, 'y' => 50)
    ),
    '九州・沖縄' => array(
        array('code' => '40', 'name' => '福岡', 'x' => 48, 'y' => 45),
        array('code' => '41', 'name' => '佐賀', 'x' => 45, 'y' => 45),
        array('code' => '42', 'name' => '長崎', 'x' => 42, 'y' => 47),
        array('code' => '43', 'name' => '熊本', 'x' => 47, 'y' => 50),
        array('code' => '44', 'name' => '大分', 'x' => 50, 'y' => 48),
        array('code' => '45', 'name' => '宮崎', 'x' => 50, 'y' => 53),
        array('code' => '46', 'name' => '鹿児島', 'x' => 47, 'y' => 56),
        array('code' => '47', 'name' => '沖縄', 'x' => 35, 'y' => 60)
    )
);
?>

<!-- シンプル日本地図セクション -->
<div class="simple-japan-map-section">
    <div class="map-container">
        <!-- 背景のSVG日本地図 -->
        <div class="japan-map-bg">
            <svg viewBox="0 0 100 70" xmlns="http://www.w3.org/2000/svg" class="background-map">
                <!-- 日本の輪郭（簡略化） -->
                <path d="M 85 5 L 90 8 L 88 15 L 85 20 L 87 25 L 85 35 L 88 40 L 85 45 L 80 48 
                         L 75 45 L 70 48 L 65 50 L 60 48 L 55 45 L 50 48 L 45 50 L 40 45 
                         L 38 40 L 42 35 L 45 30 L 50 28 L 55 30 L 60 28 L 65 25 L 70 20 
                         L 75 15 L 80 10 L 85 5 Z" 
                      fill="none" 
                      stroke="#e0e0e0" 
                      stroke-width="0.5"
                      opacity="0.3"/>
                
                <!-- 都道府県ポイント -->
                <?php foreach ($regions as $region_name => $prefectures): ?>
                    <?php foreach ($prefectures as $pref): 
                        $count = isset($prefecture_counts[$pref['name']]) ? 
                                $prefecture_counts[$pref['name']]['count'] : 0;
                        $opacity = $count > 0 ? '0.6' : '0.2';
                        $radius = $count > 0 ? '1.5' : '1';
                    ?>
                    <circle cx="<?php echo $pref['x']; ?>" 
                            cy="<?php echo $pref['y']; ?>" 
                            r="<?php echo $radius; ?>" 
                            fill="#4CAF50" 
                            opacity="<?php echo $opacity; ?>"/>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </svg>
        </div>
        
        <!-- メインコンテンツ -->
        <div class="map-content">
            <div class="map-header">
                <h3 class="section-title">
                    <span class="title-en">REGIONAL SEARCH</span>
                    <span class="title-ja">地域から探す</span>
                </h3>
            </div>
            
            <div class="map-grid-wrapper">
                <!-- 左側：スマートフォン風の地図表示 -->
                <div class="smartphone-view">
                    <div class="phone-frame">
                        <div class="phone-screen">
                            <?php 
                            $total_count = 0;
                            foreach ($regions as $region_name => $prefectures): 
                                foreach ($prefectures as $pref):
                                    $count = isset($prefecture_counts[$pref['name']]) ? 
                                            $prefecture_counts[$pref['name']]['count'] : 0;
                                    $total_count += $count;
                                    
                                    if (isset($prefecture_counts[$pref['name']])) {
                                        $url = $prefecture_counts[$pref['name']]['url'];
                                    } else {
                                        $url = '#';
                                    }
                                    
                                    // サイズと色の調整
                                    $size_class = $count > 10 ? 'large' : ($count > 5 ? 'medium' : 'small');
                                    $has_grants = $count > 0 ? 'has-grants' : 'no-grants';
                            ?>
                                <a href="<?php echo esc_url($url); ?>" 
                                   class="prefecture-dot <?php echo $size_class; ?> <?php echo $has_grants; ?>"
                                   style="left: <?php echo $pref['x'] - 30; ?>%; top: <?php echo $pref['y']; ?>%;"
                                   data-prefecture="<?php echo esc_attr($pref['name']); ?>"
                                   data-count="<?php echo $count; ?>"
                                   title="<?php echo esc_attr($pref['name']); ?> (<?php echo $count; ?>件)">
                                    <span class="dot"></span>
                                    <span class="label"><?php echo esc_html($pref['name']); ?></span>
                                </a>
                            <?php 
                                endforeach;
                            endforeach; 
                            ?>
                        </div>
                    </div>
                </div>
                
                <!-- 右側：地域別グリッド表示 -->
                <div class="regions-grid">
                    <?php foreach ($regions as $region_name => $prefectures): ?>
                    <div class="region-block">
                        <h4 class="region-title"><?php echo esc_html($region_name); ?></h4>
                        <div class="prefecture-list">
                            <?php foreach ($prefectures as $pref): 
                                $count = isset($prefecture_counts[$pref['name']]) ? 
                                        $prefecture_counts[$pref['name']]['count'] : 0;
                                
                                if (isset($prefecture_counts[$pref['name']])) {
                                    $url = $prefecture_counts[$pref['name']]['url'];
                                } else {
                                    $url = '#';
                                }
                            ?>
                            <a href="<?php echo esc_url($url); ?>" 
                               class="prefecture-item <?php echo $count > 0 ? 'active' : ''; ?>">
                                <span class="pref-name"><?php echo esc_html($pref['name']); ?></span>
                                <span class="pref-count"><?php echo $count; ?></span>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- 下部の検索ボタン -->
            <div class="search-action">
                <p class="action-text">条件を絞り込んで、あなたに最適な助成金を見つけましょう</p>
                <a href="<?php echo get_post_type_archive_link('grant'); ?>" class="search-button">
                    <i class="fas fa-search"></i>
                    <span>助成金を検索</span>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* シンプル日本地図セクション */
.simple-japan-map-section {
    padding: 60px 0;
    background: #f8f9fa;
    position: relative;
    overflow: hidden;
}

.map-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    position: relative;
}

/* 背景のSVG地図 */
.japan-map-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    opacity: 0.1;
    pointer-events: none;
}

.background-map {
    width: 100%;
    height: 100%;
    max-width: 1400px;
    margin: 0 auto;
}

/* メインコンテンツ */
.map-content {
    position: relative;
    z-index: 1;
}

.map-header {
    text-align: center;
    margin-bottom: 50px;
}

.section-title {
    margin-bottom: 20px;
}

.title-en {
    display: block;
    font-size: 12px;
    letter-spacing: 0.2em;
    color: #999;
    margin-bottom: 8px;
    text-transform: uppercase;
}

.title-ja {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a1a;
}

/* グリッドラッパー */
.map-grid-wrapper {
    display: grid;
    grid-template-columns: 400px 1fr;
    gap: 60px;
    align-items: start;
    margin-bottom: 60px;
}

/* スマートフォン風表示 */
.smartphone-view {
    display: flex;
    justify-content: center;
}

.phone-frame {
    width: 320px;
    height: 500px;
    background: #ffffff;
    border: 3px solid #1a1a1a;
    border-radius: 30px;
    padding: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    position: relative;
}

.phone-frame::before {
    content: '';
    position: absolute;
    top: 10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 4px;
    background: #1a1a1a;
    border-radius: 2px;
}

.phone-screen {
    width: 100%;
    height: 100%;
    background: #fafafa;
    border-radius: 20px;
    position: relative;
    overflow: hidden;
}

/* 都道府県ドット */
.prefecture-dot {
    position: absolute;
    text-decoration: none;
    transition: all 0.3s ease;
    z-index: 1;
}

.prefecture-dot .dot {
    display: block;
    border-radius: 50%;
    background: #666;
    transition: all 0.3s ease;
}

.prefecture-dot.small .dot {
    width: 8px;
    height: 8px;
}

.prefecture-dot.medium .dot {
    width: 12px;
    height: 12px;
}

.prefecture-dot.large .dot {
    width: 16px;
    height: 16px;
}

.prefecture-dot.has-grants .dot {
    background: #4CAF50;
}

.prefecture-dot:hover .dot {
    transform: scale(1.5);
    background: #2196F3;
}

.prefecture-dot .label {
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    font-size: 9px;
    color: #666;
    white-space: nowrap;
    margin-top: 2px;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.prefecture-dot:hover .label {
    opacity: 1;
}

/* 地域別グリッド */
.regions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 30px;
}

.region-block {
    background: #ffffff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.region-title {
    font-size: 16px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f0f0f0;
}

.prefecture-list {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
}

.prefecture-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: #f8f9fa;
    border-radius: 6px;
    text-decoration: none;
    color: #666;
    font-size: 14px;
    transition: all 0.3s ease;
}

.prefecture-item:hover {
    background: #1a1a1a;
    color: #ffffff;
}

.prefecture-item.active {
    background: #e8f5e9;
    color: #2e7d32;
    font-weight: 600;
}

.prefecture-item.active:hover {
    background: #4CAF50;
    color: #ffffff;
}

.pref-count {
    font-weight: 700;
    margin-left: 8px;
}

/* 検索アクション */
.search-action {
    text-align: center;
    padding: 40px;
    background: #1a1a1a;
    border-radius: 16px;
    color: #ffffff;
}

.action-text {
    font-size: 16px;
    margin-bottom: 24px;
    opacity: 0.9;
}

.search-button {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 16px 32px;
    background: #4CAF50;
    color: #ffffff;
    border-radius: 50px;
    text-decoration: none;
    font-size: 16px;
    font-weight: 700;
    transition: all 0.3s ease;
}

.search-button:hover {
    background: #45a049;
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(76, 175, 80, 0.3);
}

/* レスポンシブ */
@media (max-width: 1024px) {
    .map-grid-wrapper {
        grid-template-columns: 1fr;
        gap: 40px;
    }
    
    .smartphone-view {
        margin: 0 auto;
    }
    
    .regions-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .phone-frame {
        width: 280px;
        height: 450px;
    }
    
    .regions-grid {
        grid-template-columns: 1fr;
    }
    
    .title-ja {
        font-size: 24px;
    }
    
    .search-action {
        padding: 30px 20px;
    }
}

@media (max-width: 480px) {
    .simple-japan-map-section {
        padding: 40px 0;
    }
    
    .map-grid-wrapper {
        gap: 30px;
    }
    
    .phone-frame {
        width: 100%;
        max-width: 280px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 都道府県ドットのインタラクション
    const dots = document.querySelectorAll('.prefecture-dot');
    
    dots.forEach(dot => {
        dot.addEventListener('mouseenter', function() {
            const count = this.dataset.count;
            if (count > 0) {
                this.style.zIndex = '10';
            }
        });
        
        dot.addEventListener('mouseleave', function() {
            this.style.zIndex = '1';
        });
    });
    
    // 地域ブロックのアニメーション
    const regionBlocks = document.querySelectorAll('.region-block');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100);
            }
        });
    }, {
        threshold: 0.1
    });
    
    regionBlocks.forEach(block => {
        block.style.opacity = '0';
        block.style.transform = 'translateY(20px)';
        block.style.transition = 'all 0.5s ease';
        observer.observe(block);
    });
});
</script>