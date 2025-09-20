<?php
/**
 * Japan Map JS - Advanced Interactive Map Component
 * 最新のJapan Map JSプラグインを使用したインタラクティブ地図
 * 
 * @package Grant_Insight
 * @version 2.0.0
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

// 都道府県データの整形
$prefecture_grants = array();
$total_grants = 0;
$max_grants = 0;

// 都道府県名のマッピング（正規化用）
$prefecture_names = array(
    1 => '北海道', 2 => '青森', 3 => '岩手', 4 => '宮城', 5 => '秋田',
    6 => '山形', 7 => '福島', 8 => '茨城', 9 => '栃木', 10 => '群馬',
    11 => '埼玉', 12 => '千葉', 13 => '東京', 14 => '神奈川', 15 => '新潟',
    16 => '富山', 17 => '石川', 18 => '福井', 19 => '山梨', 20 => '長野',
    21 => '岐阜', 22 => '静岡', 23 => '愛知', 24 => '三重', 25 => '滋賀',
    26 => '京都', 27 => '大阪', 28 => '兵庫', 29 => '奈良', 30 => '和歌山',
    31 => '鳥取', 32 => '島根', 33 => '岡山', 34 => '広島', 35 => '山口',
    36 => '徳島', 37 => '香川', 38 => '愛媛', 39 => '高知', 40 => '福岡',
    41 => '佐賀', 42 => '長崎', 43 => '熊本', 44 => '大分', 45 => '宮崎',
    46 => '鹿児島', 47 => '沖縄'
);

// データ整形
foreach ($prefectures_data as $pref) {
    $pref_name = str_replace(array('県', '都', '府'), '', $pref->name);
    
    // 都道府県コードを検索
    $pref_code = array_search($pref_name, $prefecture_names);
    
    if ($pref_code !== false) {
        $prefecture_grants[$pref_code] = array(
            'name' => $pref->name,
            'slug' => $pref->slug,
            'count' => $pref->count,
            'url' => add_query_arg('grant_prefecture', $pref->slug, get_post_type_archive_link('grant'))
        );
        $total_grants += $pref->count;
        $max_grants = max($max_grants, $pref->count);
    }
}

// カラーパレット（黒基調のグラデーション）
$base_colors = array(
    'hokkaido' => '#1a1a1a',  // 北海道
    'tohoku' => '#2a2a2a',    // 東北
    'kanto' => '#3a3a3a',     // 関東
    'chubu' => '#333333',     // 中部
    'kinki' => '#404040',     // 近畿
    'chugoku' => '#4a4a4a',   // 中国
    'shikoku' => '#525252',   // 四国
    'kyushu' => '#5a5a5a'     // 九州・沖縄
);

$archive_base_url = get_post_type_archive_link('grant');
?>

<!-- Japan Map JS Advanced Component -->
<div class="japan-map-advanced-container" id="japan-map-section">
    <div class="map-header-section">
        <h3 class="map-title">
            <span class="title-label">INTERACTIVE MAP</span>
            <span class="title-main">都道府県から助成金を探す</span>
        </h3>
        <p class="map-description">
            地図上の都道府県をクリックして、地域別の助成金を確認できます
        </p>
    </div>

    <div class="map-content-wrapper">
        <!-- 地図表示エリア -->
        <div class="map-display-area">
            <div id="japan-map-js" class="japan-map-container"></div>
            
            <!-- ズームコントロール -->
            <div class="map-controls">
                <button id="zoom-in" class="zoom-btn" aria-label="拡大">
                    <i class="fas fa-plus"></i>
                </button>
                <button id="zoom-out" class="zoom-btn" aria-label="縮小">
                    <i class="fas fa-minus"></i>
                </button>
                <button id="reset-view" class="reset-btn" aria-label="リセット">
                    <i class="fas fa-undo"></i>
                </button>
            </div>
        </div>

        <!-- 情報パネル -->
        <div class="info-panel">
            <!-- 選択中の都道府県情報 -->
            <div class="selected-info">
                <h4 class="panel-title">選択中の都道府県</h4>
                <div class="prefecture-details">
                    <div class="no-selection">
                        <i class="fas fa-map-marker-alt"></i>
                        <p>都道府県を選択してください</p>
                    </div>
                    <div class="selection-content" style="display:none;">
                        <h3 class="selected-name"></h3>
                        <div class="grant-stats">
                            <div class="stat-item">
                                <span class="stat-number">0</span>
                                <span class="stat-label">件の助成金</span>
                            </div>
                        </div>
                        <a href="#" class="view-details-btn">
                            <span>助成金を見る</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 全国統計 -->
            <div class="nationwide-stats">
                <h4 class="panel-title">全国統計</h4>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value"><?php echo number_format($total_grants); ?></div>
                        <div class="stat-label">総助成金数</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">47</div>
                        <div class="stat-label">都道府県</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?php echo number_format(round($total_grants / 47)); ?></div>
                        <div class="stat-label">平均/県</div>
                    </div>
                </div>
            </div>

            <!-- トップ都道府県 -->
            <div class="top-prefectures">
                <h4 class="panel-title">助成金数TOP5</h4>
                <ol class="top-list">
                    <?php
                    // 助成金数でソート
                    $sorted_prefs = $prefecture_grants;
                    usort($sorted_prefs, function($a, $b) {
                        return $b['count'] - $a['count'];
                    });
                    
                    $top_5 = array_slice($sorted_prefs, 0, 5);
                    foreach ($top_5 as $index => $pref) :
                        if ($pref['count'] > 0) :
                    ?>
                    <li class="top-item">
                        <span class="rank"><?php echo $index + 1; ?></span>
                        <span class="pref-name"><?php echo esc_html($pref['name']); ?></span>
                        <span class="pref-count"><?php echo number_format($pref['count']); ?>件</span>
                    </li>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </ol>
            </div>
        </div>
    </div>

    <!-- 地域別凡例 -->
    <div class="map-legend-section">
        <div class="legend-grid">
            <div class="legend-item">
                <span class="color-indicator" style="background:#4CAF50;"></span>
                <span class="legend-label">助成金あり</span>
            </div>
            <div class="legend-item">
                <span class="color-indicator" style="background:#666666;"></span>
                <span class="legend-label">助成金なし</span>
            </div>
            <div class="legend-item">
                <span class="color-indicator" style="background:#FFC107;"></span>
                <span class="legend-label">選択中</span>
            </div>
        </div>
    </div>
</div>

<!-- Japan Map JSライブラリの読み込み -->
<script src="https://unpkg.com/japan-map-js@1.0.1/dist/jpmap.min.js"></script>

<!-- カスタムスタイル -->
<style>
/* メインコンテナ */
.japan-map-advanced-container {
    background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
    border-radius: 24px;
    padding: 40px;
    margin: 60px auto;
    max-width: 1400px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
}

/* ヘッダーセクション */
.map-header-section {
    text-align: center;
    margin-bottom: 40px;
    color: #ffffff;
}

.map-title {
    margin-bottom: 16px;
}

.title-label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 0.2em;
    color: #4CAF50;
    margin-bottom: 8px;
    text-transform: uppercase;
}

.title-main {
    display: block;
    font-size: 32px;
    font-weight: 800;
    line-height: 1.2;
}

.map-description {
    font-size: 16px;
    color: #b0b0b0;
    max-width: 600px;
    margin: 0 auto;
}

/* コンテンツラッパー */
.map-content-wrapper {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 40px;
    align-items: start;
}

/* 地図表示エリア */
.map-display-area {
    position: relative;
    background: #0a0a0a;
    border-radius: 16px;
    padding: 30px;
    border: 2px solid #333333;
}

.japan-map-container {
    width: 100%;
    min-height: 600px;
    position: relative;
}

/* ズームコントロール */
.map-controls {
    position: absolute;
    top: 20px;
    right: 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    z-index: 100;
}

.zoom-btn, .reset-btn {
    width: 40px;
    height: 40px;
    background: rgba(0, 0, 0, 0.8);
    border: 2px solid #4CAF50;
    border-radius: 8px;
    color: #4CAF50;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    font-size: 16px;
}

.zoom-btn:hover, .reset-btn:hover {
    background: #4CAF50;
    color: #000000;
    transform: scale(1.1);
}

/* 情報パネル */
.info-panel {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.info-panel > div {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 16px;
    padding: 24px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.panel-title {
    font-size: 14px;
    font-weight: 600;
    color: #4CAF50;
    margin-bottom: 16px;
    text-transform: uppercase;
    letter-spacing: 0.1em;
}

/* 選択情報 */
.no-selection {
    text-align: center;
    padding: 40px 20px;
    color: #666666;
}

.no-selection i {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.selection-content .selected-name {
    font-size: 28px;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 20px;
}

.grant-stats {
    background: rgba(76, 175, 80, 0.1);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
    text-align: center;
}

.stat-item .stat-number {
    font-size: 36px;
    font-weight: 900;
    color: #4CAF50;
    display: block;
}

.stat-item .stat-label {
    font-size: 14px;
    color: #b0b0b0;
}

.view-details-btn {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: 16px 20px;
    background: #4CAF50;
    color: #000000;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.view-details-btn:hover {
    background: #45a049;
    transform: translateX(4px);
}

/* 統計グリッド */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}

.stat-card {
    background: rgba(0, 0, 0, 0.3);
    border-radius: 8px;
    padding: 16px;
    text-align: center;
}

.stat-card .stat-value {
    font-size: 24px;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 4px;
}

.stat-card .stat-label {
    font-size: 11px;
    color: #888888;
    text-transform: uppercase;
}

/* トップリスト */
.top-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.top-item {
    display: flex;
    align-items: center;
    padding: 12px;
    background: rgba(0, 0, 0, 0.3);
    border-radius: 8px;
    margin-bottom: 8px;
    transition: all 0.3s ease;
}

.top-item:hover {
    background: rgba(76, 175, 80, 0.1);
    transform: translateX(4px);
}

.top-item .rank {
    width: 28px;
    height: 28px;
    background: #4CAF50;
    color: #000000;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 12px;
    margin-right: 12px;
}

.top-item .pref-name {
    flex: 1;
    color: #ffffff;
    font-weight: 500;
}

.top-item .pref-count {
    color: #4CAF50;
    font-weight: 600;
    font-size: 14px;
}

/* 凡例 */
.map-legend-section {
    margin-top: 32px;
    padding-top: 32px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.legend-grid {
    display: flex;
    justify-content: center;
    gap: 40px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.color-indicator {
    width: 24px;
    height: 24px;
    border-radius: 4px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.legend-label {
    color: #b0b0b0;
    font-size: 14px;
}

/* Japan Map JSカスタマイズ */
.jpmap-container {
    background: transparent !important;
}

.jpmap-prefecture {
    transition: all 0.3s ease !important;
    cursor: pointer !important;
}

.jpmap-prefecture-name {
    font-size: 11px !important;
    font-weight: 600 !important;
    fill: #ffffff !important;
}

/* レスポンシブ対応 */
@media (max-width: 1200px) {
    .map-content-wrapper {
        grid-template-columns: 1fr;
    }
    
    .info-panel {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
    
    .selected-info {
        grid-column: span 3;
    }
}

@media (max-width: 768px) {
    .japan-map-advanced-container {
        padding: 24px;
        margin: 40px 16px;
    }
    
    .map-content-wrapper {
        gap: 24px;
    }
    
    .info-panel {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(3, 1fr);
    }
    
    .title-main {
        font-size: 24px;
    }
    
    .map-display-area {
        padding: 20px;
    }
}

/* ツールチップ */
.map-tooltip {
    position: absolute;
    background: rgba(0, 0, 0, 0.95);
    color: #ffffff;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 14px;
    pointer-events: none;
    z-index: 1000;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
    border: 1px solid #4CAF50;
    display: none;
}

.map-tooltip.active {
    display: block;
}

.tooltip-prefecture {
    font-weight: 700;
    margin-bottom: 4px;
}

.tooltip-count {
    color: #4CAF50;
}

/* アニメーション */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.pulse-animation {
    animation: pulse 2s infinite;
}
</style>

<!-- カスタムJavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // PHPから渡されるデータ
    const prefectureData = <?php echo json_encode($prefecture_grants); ?>;
    const archiveBaseUrl = '<?php echo esc_url($archive_base_url); ?>';
    
    // 都道府県リンクの設定
    const areaLinks = {};
    for (let code in prefectureData) {
        areaLinks[code] = prefectureData[code].url;
    }
    
    // カラー設定（助成金数に応じた色分け）
    const areas = [];
    for (let i = 1; i <= 47; i++) {
        const data = prefectureData[i] || { count: 0 };
        const hasGrants = data.count > 0;
        
        // 地域別の基本色設定
        let baseColor, hoverColor;
        if (i === 1) { // 北海道
            baseColor = hasGrants ? '#2c3e50' : '#666666';
            hoverColor = '#4CAF50';
        } else if (i <= 7) { // 東北
            baseColor = hasGrants ? '#34495e' : '#666666';
            hoverColor = '#4CAF50';
        } else if (i <= 14) { // 関東
            baseColor = hasGrants ? '#16a085' : '#666666';
            hoverColor = '#4CAF50';
        } else if (i <= 23) { // 中部
            baseColor = hasGrants ? '#27ae60' : '#666666';
            hoverColor = '#4CAF50';
        } else if (i <= 30) { // 近畿
            baseColor = hasGrants ? '#2980b9' : '#666666';
            hoverColor = '#4CAF50';
        } else if (i <= 35) { // 中国
            baseColor = hasGrants ? '#8e44ad' : '#666666';
            hoverColor = '#4CAF50';
        } else if (i <= 39) { // 四国
            baseColor = hasGrants ? '#d35400' : '#666666';
            hoverColor = '#4CAF50';
        } else { // 九州・沖縄
            baseColor = hasGrants ? '#c0392b' : '#666666';
            hoverColor = '#4CAF50';
        }
        
        areas.push({
            code: i,
            name: <?php echo json_encode($prefecture_names); ?>[i],
            color: baseColor,
            hoverColor: hoverColor
        });
    }
    
    // Japan Map JSの初期化
    const mapInstance = new jpmap.japanMap(document.getElementById('japan-map-js'), {
        areas: areas,
        showsPrefectureName: true,
        width: 900,
        height: 600,
        movesIslands: true,
        borderLineColor: "#333333",
        borderLineWidth: 1,
        lang: 'ja',
        onSelect: function(data) {
            const prefData = prefectureData[data.area.code];
            
            if (prefData) {
                // 選択情報の更新
                document.querySelector('.no-selection').style.display = 'none';
                document.querySelector('.selection-content').style.display = 'block';
                
                document.querySelector('.selected-name').textContent = prefData.name;
                document.querySelector('.stat-number').textContent = prefData.count;
                document.querySelector('.view-details-btn').href = prefData.url;
                
                // 選択エフェクト
                document.querySelector('.selection-content').classList.add('pulse-animation');
                setTimeout(() => {
                    document.querySelector('.selection-content').classList.remove('pulse-animation');
                }, 2000);
            }
        },
        onHover: function(data) {
            // ツールチップ表示（オプション）
            if (data && prefectureData[data.area.code]) {
                const prefData = prefectureData[data.area.code];
                showTooltip(event, prefData.name, prefData.count);
            }
        }
    });
    
    // ズーム機能
    let currentScale = 1;
    const mapContainer = document.getElementById('japan-map-js');
    
    document.getElementById('zoom-in').addEventListener('click', function() {
        currentScale = Math.min(currentScale + 0.2, 2);
        mapContainer.style.transform = `scale(${currentScale})`;
    });
    
    document.getElementById('zoom-out').addEventListener('click', function() {
        currentScale = Math.max(currentScale - 0.2, 0.5);
        mapContainer.style.transform = `scale(${currentScale})`;
    });
    
    document.getElementById('reset-view').addEventListener('click', function() {
        currentScale = 1;
        mapContainer.style.transform = 'scale(1)';
        mapContainer.style.transition = 'transform 0.3s ease';
    });
    
    // ツールチップ関数
    function showTooltip(event, prefName, count) {
        let tooltip = document.querySelector('.map-tooltip');
        if (!tooltip) {
            tooltip = document.createElement('div');
            tooltip.className = 'map-tooltip';
            document.body.appendChild(tooltip);
        }
        
        tooltip.innerHTML = `
            <div class="tooltip-prefecture">${prefName}</div>
            <div class="tooltip-count">助成金: ${count}件</div>
        `;
        
        tooltip.style.left = (event.pageX + 10) + 'px';
        tooltip.style.top = (event.pageY - 30) + 'px';
        tooltip.classList.add('active');
        
        // マウスが離れたら非表示
        setTimeout(() => {
            tooltip.classList.remove('active');
        }, 2000);
    }
    
    // 初期アニメーション
    setTimeout(() => {
        document.querySelector('.japan-map-advanced-container').style.opacity = '1';
    }, 100);
});
</script>