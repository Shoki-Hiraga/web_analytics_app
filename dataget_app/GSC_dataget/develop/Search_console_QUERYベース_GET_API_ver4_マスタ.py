import sys
import os
sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '..')))
from setting_file.header import *
# 個別URLリストインスタンス
from setting_file.Search_Console_set.query_base_master import Queries

# ファイルパス
file_directory = file_path.file_directory # file_path.py で定義したファイルディレクトリを指定
file_name = "Search Console_API_Query.csv"
output_file = os.path.join(file_directory, file_name)


# 対象のサイトURLを指定します
site_url = 'https://www.qsha-oh.com/'

# JSONファイルのパスを指定
SERVICE_ACCOUNT_FILE = api_json.qsha_oh

# Search Console APIの認証情報を指定
credentials = service_account.Credentials.from_service_account_file(
    SERVICE_ACCOUNT_FILE, scopes=['https://www.googleapis.com/auth/webmasters.readonly']
)

# Search Console APIのバージョンとプロジェクトIDを指定します
api_version = 'v3'
service = build('webmasters', api_version, credentials=credentials)

# 検索クエリに一致したデータを取得する関数を定義します
def get_search_query_data(site_url, query):
    request = {
        'startDate': '2024-11-15',
        'endDate': '2024-11-25',
        'dimensions': ['query', 'page'],  # dimensionsに'page'を追加
        'searchType': 'web',
        'dimensionFilterGroups': [{
            'filters': [{
                'dimension': 'query',
               'operator': 'equals',
            #    'operator': 'contains',
                'expression': query
            }]
        }]
    }

    try:
        response = service.searchanalytics().query(siteUrl=site_url, body=request).execute()
        return response.get('rows', []), query
    except Exception as e:
        print(f'Error retrieving data for query {query}: {e}')
        return [], query


header_row = ['検索クエリ', 'URL', '表示回数', 'クリック数', 'クリック率', '掲載順位']

# 全体の結果を格納するリストを作成
all_results = []

# CSVファイルのヘッダー行を書き込む
with open(output_file, 'w', newline='', encoding='utf-8') as csvfile:
    csv_writer = csv.writer(csvfile)
    csv_writer.writerow(header_row)
    # # スプレッドシートにヘッダー行を書き込む
    # worksheet.append_row(header_row, value_input_option='USER_ENTERED')

try:
    for query in Queries:
        # クエリの統計情報を取得
        search_query_data, original_query = get_search_query_data(site_url, query)

        # ランダムな遅延処理を追加
        delay = random.uniform(1.0, 2.5)
        print(f'遅延処理 {delay} 秒')
        time.sleep(delay)

        # 結果をCSVファイルに追加
        with open(output_file, 'a', newline='', encoding='utf-8') as csvfile:
            csv_writer = csv.writer(csvfile)

            if not search_query_data:  # データがない場合
                csv_writer.writerow([original_query, 'not-URLS', '0', '0', '0'])
                # worksheet.append_row([original_query, '0', '0', '0', '0'])

                print(f'検索クエリ: {original_query}, データがありません')
            else:
                for row in search_query_data:
                    query = row['keys'][0]
                    page_url = row['keys'][1]  # URLを取得
                    impressions = row.get('impressions', '-')
                    clicks = row.get('clicks', '-')
                    ctr = row.get('ctr', '-')
                    position = row.get('position', '-')
                    csv_writer.writerow([query, page_url, impressions, clicks, ctr, position])
                    # worksheet.append_row([query, impressions, clicks, ctr, position])
                    print(f'検索クエリ: {query}, URL: {page_url}, 表示回数: {impressions}, クリック数: {clicks}, クリック率: {ctr}, 掲載順位: {position}')

    print(f'CSVファイルを以下のディレクトリにエクスポートしました: {output_file}')

except Exception as err:
    print(f'An error occurred: {err}')
