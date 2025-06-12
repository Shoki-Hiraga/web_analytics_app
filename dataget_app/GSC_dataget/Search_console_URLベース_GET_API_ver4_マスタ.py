import sys
import os
sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '..')))
from setting_file.header import *
# 個別URLリストインスタンス
from setting_file.Search_Console_set.url_base_master import URLS


# ファイルパス
file_directory = file_path.file_directory # file_path.py で定義したファイルディレクトリを指定
file_name = "Search Console_API_URL.csv"
output_file = os.path.join(file_directory, file_name)

# URLごとに出力するCSVファイルのファイル名
header_row = ['URL', '検索クエリ', '表示回数', 'クリック数', 'クリック率', '掲載順位']

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

# 指定したURLに一致したデータを取得する関数を定義します
def get_search_url_data(site_url, page_url):
    request = {
        'startDate': '2024-04-01',
        'endDate': '2024-11-01',
        'dimensions': ['page', 'query'],  # 'query'を追加して検索クエリを取得するようにします
        'searchType': 'web',
        'dimensionFilterGroups': [{
            'filters': [{
                'dimension': 'page',
                'operator': 'equals',
                # 'operator': 'contains',
                'expression': page_url
            }]
        }]
    }

    try:
        response = service.searchanalytics().query(siteUrl=site_url, body=request).execute()
        return response.get('rows', []), page_url
    except Exception as e:
        print(f'Error retrieving data for URL {page_url}: {e}')
        return [], page_url


try:
    with open(output_file, 'w', newline='', encoding='utf-8') as csvfile:
        csv_writer = csv.writer(csvfile)
        # ヘッダー行を書き込む
        csv_writer.writerow(header_row)
        # # スプレッドシートにヘッダー行を書き込む
        # worksheet.append_row(header_row, value_input_option='USER_ENTERED')

        for url in URLS:
            # URLの統計情報を取得
            search_url_data, original_url = get_search_url_data(site_url, url)

            # ランダムな遅延処理を追加
            delay = random.uniform(1.5, 2.5)
            print(f'遅延処理 {delay} 秒')
            time.sleep(delay)

            if not search_url_data:  # データがない場合
                csv_writer.writerow([original_url, '', '0', '0', '0', '0'])
                # worksheet.append_row([original_url, '', '0', '0', '0', '0'])
                print(f'URL: {original_url}, データがありません')
            else:
                for row in search_url_data:
                    page_url = row['keys'][0]
                    query = row['keys'][1]
                    impressions = row.get('impressions', '-')
                    clicks = row.get('clicks', '-')
                    ctr = row.get('ctr', '-')
                    position = row.get('position', '-')

                    # CSVファイルに書き込む
                    csv_writer.writerow([page_url, query, impressions, clicks, ctr, position])

                    # # スプレッドシートに書き込む
                    # worksheet.append_row([page_url, query, impressions, clicks, ctr, position])

                    print(f'URL: {page_url}, 検索クエリ: {query}, 表示回数: {impressions}, クリック数: {clicks}, クリック率: {ctr}, 平均掲載順位: {position}')

    print(f'CSVファイルを以下のディレクトリにエクスポートしました: {output_file}')

except Exception as err:
    print(f'An error occurred: {err}')
