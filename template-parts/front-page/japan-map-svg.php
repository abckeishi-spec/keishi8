<?php
/**
 * Interactive Japan SVG Map Component
 * 日本地図インタラクティブSVGコンポーネント
 * Based on Geolonia's Japanese Prefectures SVG
 * 
 * @package Grant_Insight
 * @version 1.0.0
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

// 都道府県コードと名前のマッピング
$prefecture_map = array();
foreach ($prefectures_data as $pref) {
    $prefecture_map[$pref->slug] = array(
        'name' => $pref->name,
        'count' => $pref->count,
        'url' => add_query_arg('grant_prefecture', $pref->slug, get_post_type_archive_link('grant'))
    );
}
?>

<div class="japan-map-container" id="japan-svg-map">
    <div class="map-header">
        <h3 class="map-title">
            <span class="title-en">PREFECTURE SELECT</span>
            <span class="title-ja">都道府県から選ぶ</span>
        </h3>
        <p class="map-description">地図から都道府県を選択してください</p>
    </div>
    
    <div class="map-wrapper">
        <!-- SVG日本地図 -->
        <div class="svg-map-container">
            <?php
            // SVGファイルを読み込み
            $svg_file = get_template_directory() . '/assets/japan-map.svg';
            if (file_exists($svg_file)) {
                $svg_content = file_get_contents($svg_file);
                // クラス名を追加
                $svg_content = str_replace('<svg', '<svg class="geolonia-svg-map"', $svg_content);
                echo $svg_content;
            } else {
                // フォールバックインラインSVG
                ?>
                <svg class="geolonia-svg-map" viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg">
            <!-- 北海道 -->
            <g class="prefecture hokkaido" data-code="01" data-name="北海道">
                <path d="M 835 150 Q 850 120, 870 130 L 900 180 Q 890 220, 850 240 L 780 230 Q 760 200, 770 170 Z"/>
            </g>
            
            <!-- 東北地方 -->
            <g class="tohoku-region">
                <!-- 青森 -->
                <g class="prefecture aomori" data-code="02" data-name="青森">
                    <path d="M 780 280 L 820 280 L 820 320 L 780 320 Z"/>
                </g>
                <!-- 岩手 -->
                <g class="prefecture iwate" data-code="03" data-name="岩手">
                    <path d="M 820 320 L 860 320 L 860 380 L 820 380 Z"/>
                </g>
                <!-- 宮城 -->
                <g class="prefecture miyagi" data-code="04" data-name="宮城">
                    <path d="M 820 380 L 860 380 L 860 420 L 820 420 Z"/>
                </g>
                <!-- 秋田 -->
                <g class="prefecture akita" data-code="05" data-name="秋田">
                    <path d="M 780 320 L 820 320 L 820 380 L 780 380 Z"/>
                </g>
                <!-- 山形 -->
                <g class="prefecture yamagata" data-code="06" data-name="山形">
                    <path d="M 780 380 L 820 380 L 820 420 L 780 420 Z"/>
                </g>
                <!-- 福島 -->
                <g class="prefecture fukushima" data-code="07" data-name="福島">
                    <path d="M 780 420 L 860 420 L 860 460 L 780 460 Z"/>
                </g>
            </g>
            
            <!-- 関東地方 -->
            <g class="kanto-region">
                <!-- 茨城 -->
                <g class="prefecture ibaraki" data-code="08" data-name="茨城">
                    <path d="M 800 460 L 840 460 L 840 500 L 800 500 Z"/>
                </g>
                <!-- 栃木 -->
                <g class="prefecture tochigi" data-code="09" data-name="栃木">
                    <path d="M 760 460 L 800 460 L 800 500 L 760 500 Z"/>
                </g>
                <!-- 群馬 -->
                <g class="prefecture gunma" data-code="10" data-name="群馬">
                    <path d="M 720 460 L 760 460 L 760 500 L 720 500 Z"/>
                </g>
                <!-- 埼玉 -->
                <g class="prefecture saitama" data-code="11" data-name="埼玉">
                    <path d="M 760 500 L 800 500 L 800 540 L 760 540 Z"/>
                </g>
                <!-- 千葉 -->
                <g class="prefecture chiba" data-code="12" data-name="千葉">
                    <path d="M 840 500 L 880 500 L 880 560 L 840 560 Z"/>
                </g>
                <!-- 東京 -->
                <g class="prefecture tokyo" data-code="13" data-name="東京">
                    <path d="M 800 540 L 840 540 L 840 580 L 800 580 Z"/>
                </g>
                <!-- 神奈川 -->
                <g class="prefecture kanagawa" data-code="14" data-name="神奈川">
                    <path d="M 800 580 L 840 580 L 840 620 L 800 620 Z"/>
                </g>
            </g>
            
            <!-- 中部地方 -->
            <g class="chubu-region">
                <!-- 新潟 -->
                <g class="prefecture niigata" data-code="15" data-name="新潟">
                    <path d="M 680 420 L 740 420 L 740 480 L 680 480 Z"/>
                </g>
                <!-- 富山 -->
                <g class="prefecture toyama" data-code="16" data-name="富山">
                    <path d="M 620 480 L 680 480 L 680 520 L 620 520 Z"/>
                </g>
                <!-- 石川 -->
                <g class="prefecture ishikawa" data-code="17" data-name="石川">
                    <path d="M 560 480 L 620 480 L 620 540 L 560 540 Z"/>
                </g>
                <!-- 福井 -->
                <g class="prefecture fukui" data-code="18" data-name="福井">
                    <path d="M 560 540 L 620 540 L 620 580 L 560 580 Z"/>
                </g>
                <!-- 山梨 -->
                <g class="prefecture yamanashi" data-code="19" data-name="山梨">
                    <path d="M 720 540 L 760 540 L 760 580 L 720 580 Z"/>
                </g>
                <!-- 長野 -->
                <g class="prefecture nagano" data-code="20" data-name="長野">
                    <path d="M 680 500 L 720 500 L 720 560 L 680 560 Z"/>
                </g>
                <!-- 岐阜 -->
                <g class="prefecture gifu" data-code="21" data-name="岐阜">
                    <path d="M 620 520 L 680 520 L 680 580 L 620 580 Z"/>
                </g>
                <!-- 静岡 -->
                <g class="prefecture shizuoka" data-code="22" data-name="静岡">
                    <path d="M 720 580 L 800 580 L 800 620 L 720 620 Z"/>
                </g>
                <!-- 愛知 -->
                <g class="prefecture aichi" data-code="23" data-name="愛知">
                    <path d="M 680 580 L 720 580 L 720 620 L 680 620 Z"/>
                </g>
            </g>
            
            <!-- 近畿地方 -->
            <g class="kinki-region">
                <!-- 三重 -->
                <g class="prefecture mie" data-code="24" data-name="三重">
                    <path d="M 640 620 L 680 620 L 680 680 L 640 680 Z"/>
                </g>
                <!-- 滋賀 -->
                <g class="prefecture shiga" data-code="25" data-name="滋賀">
                    <path d="M 600 580 L 640 580 L 640 620 L 600 620 Z"/>
                </g>
                <!-- 京都 -->
                <g class="prefecture kyoto" data-code="26" data-name="京都">
                    <path d="M 560 580 L 600 580 L 600 640 L 560 640 Z"/>
                </g>
                <!-- 大阪 -->
                <g class="prefecture osaka" data-code="27" data-name="大阪">
                    <path d="M 560 640 L 600 640 L 600 680 L 560 680 Z"/>
                </g>
                <!-- 兵庫 -->
                <g class="prefecture hyogo" data-code="28" data-name="兵庫">
                    <path d="M 500 600 L 560 600 L 560 660 L 500 660 Z"/>
                </g>
                <!-- 奈良 -->
                <g class="prefecture nara" data-code="29" data-name="奈良">
                    <path d="M 600 640 L 640 640 L 640 680 L 600 680 Z"/>
                </g>
                <!-- 和歌山 -->
                <g class="prefecture wakayama" data-code="30" data-name="和歌山">
                    <path d="M 560 680 L 620 680 L 620 720 L 560 720 Z"/>
                </g>
            </g>
            
            <!-- 中国地方 -->
            <g class="chugoku-region">
                <!-- 鳥取 -->
                <g class="prefecture tottori" data-code="31" data-name="鳥取">
                    <path d="M 440 560 L 500 560 L 500 600 L 440 600 Z"/>
                </g>
                <!-- 島根 -->
                <g class="prefecture shimane" data-code="32" data-name="島根">
                    <path d="M 380 560 L 440 560 L 440 600 L 380 600 Z"/>
                </g>
                <!-- 岡山 -->
                <g class="prefecture okayama" data-code="33" data-name="岡山">
                    <path d="M 440 600 L 500 600 L 500 640 L 440 640 Z"/>
                </g>
                <!-- 広島 -->
                <g class="prefecture hiroshima" data-code="34" data-name="広島">
                    <path d="M 380 600 L 440 600 L 440 640 L 380 640 Z"/>
                </g>
                <!-- 山口 -->
                <g class="prefecture yamaguchi" data-code="35" data-name="山口">
                    <path d="M 320 600 L 380 600 L 380 640 L 320 640 Z"/>
                </g>
            </g>
            
            <!-- 四国地方 -->
            <g class="shikoku-region">
                <!-- 徳島 -->
                <g class="prefecture tokushima" data-code="36" data-name="徳島">
                    <path d="M 520 700 L 560 700 L 560 740 L 520 740 Z"/>
                </g>
                <!-- 香川 -->
                <g class="prefecture kagawa" data-code="37" data-name="香川">
                    <path d="M 480 680 L 520 680 L 520 720 L 480 720 Z"/>
                </g>
                <!-- 愛媛 -->
                <g class="prefecture ehime" data-code="38" data-name="愛媛">
                    <path d="M 420 700 L 480 700 L 480 740 L 420 740 Z"/>
                </g>
                <!-- 高知 -->
                <g class="prefecture kochi" data-code="39" data-name="高知">
                    <path d="M 440 740 L 520 740 L 520 780 L 440 780 Z"/>
                </g>
            </g>
            
            <!-- 九州地方 -->
            <g class="kyushu-region">
                <!-- 福岡 -->
                <g class="prefecture fukuoka" data-code="40" data-name="福岡">
                    <path d="M 260 640 L 320 640 L 320 680 L 260 680 Z"/>
                </g>
                <!-- 佐賀 -->
                <g class="prefecture saga" data-code="41" data-name="佐賀">
                    <path d="M 220 680 L 260 680 L 260 720 L 220 720 Z"/>
                </g>
                <!-- 長崎 -->
                <g class="prefecture nagasaki" data-code="42" data-name="長崎">
                    <path d="M 180 680 L 220 680 L 220 740 L 180 740 Z"/>
                </g>
                <!-- 熊本 -->
                <g class="prefecture kumamoto" data-code="43" data-name="熊本">
                    <path d="M 260 720 L 300 720 L 300 760 L 260 760 Z"/>
                </g>
                <!-- 大分 -->
                <g class="prefecture oita" data-code="44" data-name="大分">
                    <path d="M 320 680 L 360 680 L 360 720 L 320 720 Z"/>
                </g>
                <!-- 宮崎 -->
                <g class="prefecture miyazaki" data-code="45" data-name="宮崎">
                    <path d="M 320 760 L 360 760 L 360 820 L 320 820 Z"/>
                </g>
                <!-- 鹿児島 -->
                <g class="prefecture kagoshima" data-code="46" data-name="鹿児島">
                    <path d="M 260 800 L 320 800 L 320 860 L 260 860 Z"/>
                </g>
                <!-- 沖縄 -->
                <g class="prefecture okinawa" data-code="47" data-name="沖縄">
                    <path d="M 160 860 L 200 860 L 200 900 L 160 900 Z"/>
                </g>
            </g>
                </svg>
            <?php } ?>
        </div>
        
        <!-- 選択情報表示エリア -->
        <div class="map-info-panel">
            <div class="selected-prefecture">
                <span class="label">選択中:</span>
                <span class="prefecture-name">未選択</span>
            </div>
            <div class="grant-count">
                <span class="count-number">0</span>
                <span class="count-unit">件</span>
            </div>
            <a href="#" class="view-grants-btn" style="display:none;">助成金を見る</a>
        </div>
    </div>
    
    <!-- 地方別凡例 -->
    <div class="map-legend">
        <div class="legend-item hokkaido-legend">
            <span class="color-box"></span>
            <span class="region-name">北海道</span>
        </div>
        <div class="legend-item tohoku-legend">
            <span class="color-box"></span>
            <span class="region-name">東北</span>
        </div>
        <div class="legend-item kanto-legend">
            <span class="color-box"></span>
            <span class="region-name">関東</span>
        </div>
        <div class="legend-item chubu-legend">
            <span class="color-box"></span>
            <span class="region-name">中部</span>
        </div>
        <div class="legend-item kinki-legend">
            <span class="color-box"></span>
            <span class="region-name">近畿</span>
        </div>
        <div class="legend-item chugoku-legend">
            <span class="color-box"></span>
            <span class="region-name">中国</span>
        </div>
        <div class="legend-item shikoku-legend">
            <span class="color-box"></span>
            <span class="region-name">四国</span>
        </div>
        <div class="legend-item kyushu-legend">
            <span class="color-box"></span>
            <span class="region-name">九州・沖縄</span>
        </div>
    </div>
</div>

<style>
/* 日本地図コンテナ */
.japan-map-container {
    background: #000000;
    border-radius: 20px;
    padding: 30px;
    margin: 40px 0;
}

.map-header {
    text-align: center;
    margin-bottom: 30px;
    color: #ffffff;
}

.map-title {
    margin-bottom: 10px;
}

.title-en {
    display: block;
    font-size: 12px;
    letter-spacing: 0.2em;
    color: #999999;
    text-transform: uppercase;
    margin-bottom: 5px;
}

.title-ja {
    display: block;
    font-size: 24px;
    font-weight: 700;
}

.map-description {
    color: #cccccc;
    font-size: 14px;
}

/* 地図ラッパー */
.map-wrapper {
    display: grid;
    grid-template-columns: 3fr 1fr;
    gap: 30px;
    align-items: start;
}

/* SVG地図コンテナ */
.svg-map-container {
    background: #1a1a1a;
    border-radius: 10px;
    padding: 20px;
    position: relative;
}

/* SVG地図スタイル */
.geolonia-svg-map {
    width: 100%;
    height: auto;
    display: block;
}

.geolonia-svg-map .prefecture {
    fill: #333333;
    stroke: #666666;
    stroke-width: 1;
    cursor: pointer;
    transition: all 0.3s ease;
}

.geolonia-svg-map .prefecture:hover {
    fill: #4CAF50;
    stroke: #ffffff;
    stroke-width: 2;
    filter: drop-shadow(0 0 10px rgba(76, 175, 80, 0.5));
}

.geolonia-svg-map .prefecture.selected {
    fill: #4CAF50;
    stroke: #ffffff;
    stroke-width: 3;
    filter: drop-shadow(0 0 15px rgba(76, 175, 80, 0.8));
}

.geolonia-svg-map .prefecture.has-grants {
    fill: #444444;
}

/* 地方別カラー */
.prefecture[data-region="hokkaido"] { fill: #2c3e50 !important; }
.prefecture[data-region="tohoku"] { fill: #34495e !important; }
.prefecture[data-region="kanto"] { fill: #16a085 !important; }
.prefecture[data-region="chubu"] { fill: #27ae60 !important; }
.prefecture[data-region="kinki"] { fill: #2980b9 !important; }
.prefecture[data-region="chugoku"] { fill: #8e44ad !important; }
.prefecture[data-region="shikoku"] { fill: #d35400 !important; }
.prefecture[data-region="kyushu"] { fill: #c0392b !important; }

/* 情報パネル */
.map-info-panel {
    background: #1a1a1a;
    border-radius: 10px;
    padding: 25px;
    color: #ffffff;
}

.selected-prefecture {
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #333333;
}

.selected-prefecture .label {
    display: block;
    font-size: 12px;
    color: #999999;
    margin-bottom: 8px;
}

.selected-prefecture .prefecture-name {
    font-size: 24px;
    font-weight: 700;
}

.grant-count {
    text-align: center;
    margin-bottom: 20px;
}

.count-number {
    font-size: 48px;
    font-weight: 900;
    color: #4CAF50;
    display: block;
}

.count-unit {
    font-size: 14px;
    color: #999999;
}

.view-grants-btn {
    display: block;
    width: 100%;
    padding: 15px;
    background: #4CAF50;
    color: #ffffff;
    text-align: center;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.view-grants-btn:hover {
    background: #45a049;
    transform: translateY(-2px);
}

/* 凡例 */
.map-legend {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 30px;
    flex-wrap: wrap;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.color-box {
    width: 20px;
    height: 20px;
    border-radius: 4px;
    border: 1px solid #666666;
}

.hokkaido-legend .color-box { background: #2c3e50; }
.tohoku-legend .color-box { background: #34495e; }
.kanto-legend .color-box { background: #16a085; }
.chubu-legend .color-box { background: #27ae60; }
.kinki-legend .color-box { background: #2980b9; }
.chugoku-legend .color-box { background: #8e44ad; }
.shikoku-legend .color-box { background: #d35400; }
.kyushu-legend .color-box { background: #c0392b; }

.region-name {
    color: #cccccc;
    font-size: 12px;
}

/* レスポンシブ */
@media (max-width: 1024px) {
    .map-wrapper {
        grid-template-columns: 1fr;
    }
    
    .map-info-panel {
        max-width: 400px;
        margin: 0 auto;
    }
}

@media (max-width: 640px) {
    .japan-map-container {
        padding: 20px;
    }
    
    .geolonia-svg-map {
        padding: 10px;
    }
    
    .map-legend {
        gap: 10px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 都道府県データ（PHPから渡される）
    const prefectureData = <?php echo json_encode($prefecture_map); ?>;
    
    // 都道府県要素を取得
    const prefectures = document.querySelectorAll('.geolonia-svg-map .prefecture');
    const infoPanel = document.querySelector('.map-info-panel');
    const prefectureName = document.querySelector('.prefecture-name');
    const countNumber = document.querySelector('.count-number');
    const viewGrantsBtn = document.querySelector('.view-grants-btn');
    
    // 各都道府県にイベントリスナーを追加
    prefectures.forEach(prefecture => {
        const code = prefecture.dataset.code;
        const name = prefecture.dataset.name;
        
        // マウスオーバー
        prefecture.addEventListener('mouseover', function() {
            // ツールチップ表示（省略可能）
        });
        
        // クリック
        prefecture.addEventListener('click', function() {
            // 選択状態をリセット
            prefectures.forEach(p => p.classList.remove('selected'));
            
            // 選択状態を追加
            this.classList.add('selected');
            
            // 情報パネルを更新
            const prefData = Object.values(prefectureData).find(p => p.name === name || p.name === name + '県');
            
            if (prefData) {
                prefectureName.textContent = prefData.name;
                countNumber.textContent = prefData.count;
                viewGrantsBtn.href = prefData.url;
                viewGrantsBtn.style.display = 'block';
            } else {
                prefectureName.textContent = name;
                countNumber.textContent = '0';
                viewGrantsBtn.style.display = 'none';
            }
        });
    });
    
    // 助成金がある都道府県をハイライト
    Object.values(prefectureData).forEach(pref => {
        if (pref.count > 0) {
            const prefElement = document.querySelector(`.prefecture[data-name*="${pref.name.replace('県', '').replace('都', '').replace('府', '')}"]`);
            if (prefElement) {
                prefElement.classList.add('has-grants');
            }
        }
    });
});
</script>