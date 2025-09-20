# AI機能セットアップガイド

## 必要な設定

### 1. OpenAI API キーの設定

WordPress管理画面から設定するか、以下のコードを`wp-config.php`に追加：

```php
define('OPENAI_API_KEY', 'あなたのAPIキー');
```

### 2. データベーステーブルの作成

以下のSQLを実行してください：

```sql
-- 会話履歴テーブル
CREATE TABLE IF NOT EXISTS wp_gi_ai_conversations (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    session_id varchar(255) NOT NULL,
    user_id bigint(20) unsigned DEFAULT NULL,
    message_type enum('user','assistant','system') NOT NULL DEFAULT 'user',
    message longtext NOT NULL,
    context longtext DEFAULT NULL,
    emotion_score decimal(3,2) DEFAULT NULL,
    intent varchar(100) DEFAULT NULL,
    confidence decimal(3,2) DEFAULT NULL,
    response_time decimal(5,3) DEFAULT NULL,
    tokens_used int(11) DEFAULT NULL,
    created_at timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY session_id (session_id),
    KEY user_id (user_id),
    KEY created_at (created_at)
);

-- 学習データテーブル
CREATE TABLE IF NOT EXISTS wp_gi_ai_learning (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    query_hash varchar(64) NOT NULL,
    original_query text NOT NULL,
    processed_query text NOT NULL,
    intent varchar(100) DEFAULT NULL,
    results longtext DEFAULT NULL,
    feedback_score tinyint(4) DEFAULT NULL,
    usage_count int(11) DEFAULT 1,
    last_used timestamp DEFAULT CURRENT_TIMESTAMP,
    created_at timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY query_hash (query_hash),
    KEY intent (intent),
    KEY last_used (last_used)
);

-- ベクトルエンベディングテーブル
CREATE TABLE IF NOT EXISTS wp_gi_vector_embeddings (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    post_id bigint(20) unsigned NOT NULL,
    embedding_type varchar(50) DEFAULT 'content',
    embedding longtext NOT NULL,
    metadata longtext DEFAULT NULL,
    created_at timestamp DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY post_id (post_id),
    KEY embedding_type (embedding_type)
);
```

### 3. 必要な設定を functions.php に追加

```php
// AI機能の有効化
add_action('init', function() {
    // OpenAI APIキーの設定
    if (defined('OPENAI_API_KEY')) {
        update_option('gi_ai_concierge_settings', [
            'openai_api_key' => OPENAI_API_KEY,
            'model' => 'gpt-4',
            'max_tokens' => 1500,
            'temperature' => 0.7,
            'enable_streaming' => true,
            'enable_voice' => true
        ]);
    }
    
    // AIコンシェルジュの初期化
    if (class_exists('GI_AI_Concierge')) {
        GI_AI_Concierge::getInstance();
    }
});
```

## 現在の実装状況

### ✅ 実装済み機能
- [x] セマンティック検索エンジン（基本版）
- [x] 感情分析システム
- [x] 学習システムフレームワーク
- [x] ストリーミング対応構造
- [x] 音声認識（Web Speech API）
- [x] AIチャットUI
- [x] 助成金データ連携

### ⚠️ 部分的実装
- [ ] OpenAI API連携（APIキー未設定）
- [ ] 実際のベクトル埋め込み（簡易版で動作中）
- [ ] リアルタイムストリーミング（デモモード）

### ❌ 未実装
- [ ] 本番用ベクトルデータベース（Pinecone等）
- [ ] 音声合成（Text-to-Speech）
- [ ] マルチモーダル対応（画像認識等）

## テスト方法

1. **基本動作確認**
   ```javascript
   // ブラウザのコンソールで実行
   if (window.aiAssistant) {
       console.log('AIアシスタント: 正常にロード');
       window.aiAssistant.openChat();
   }
   ```

2. **セマンティック検索テスト**
   - 「IT補助金」で検索
   - 「設備投資の助成金」で検索
   - 類似語での検索結果確認

3. **感情分析テスト**
   - ポジティブ：「素晴らしい助成金情報をありがとう」
   - ネガティブ：「申請が難しくて困っています」
   - ニュートラル：「助成金について教えてください」

## トラブルシューティング

### エラー: "OpenAI API key not configured"
→ wp-config.php にAPIキーを設定してください

### エラー: "Class not found"
→ すべてのファイルが正しくアップロードされているか確認

### チャットボタンが表示されない
→ JavaScript/CSSファイルが正しく読み込まれているか確認

## 推奨される改善

1. **OpenAI API の完全統合**
2. **Pinecone/Weaviate等の専用ベクトルDBの導入**
3. **キャッシュシステムの強化**
4. **レート制限の実装**
5. **管理画面からの設定UI追加**