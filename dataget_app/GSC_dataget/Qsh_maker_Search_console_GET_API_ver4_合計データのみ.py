import sys
import os
sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '..')))
from setting_file.header import *


file_directory = file_path.file_directory # file_path.py で定義したファイルディレクトリを指定 # file_path.py で定義したファイルディレクトリを指定
file_name = "Search Console_API_URL.csv"
output_file = os.path.join(file_directory, file_name)

header_row = ['URL', '合計表示回数', '合計クリック数', '平均CTR', '平均掲載順位']


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
        'startDate': '2025-02-01',
        'endDate': '2025-02-28',
        'dimensions': ['page'],
        'searchType': 'web',
        'dimensionFilterGroups': [{
            'filters': [{
                'dimension': 'page',
                # 完全一致
                'operator': 'equals',
                # 部分一致
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
        # 個別URLリストインスタンス
        from setting_file.Search_Console_set.Maker_url import URLS
        for url in URLS:
            # URLの統計情報を取得
            search_url_data, original_url = get_search_url_data(site_url, url)

            # ランダムな遅延処理を追加
            delay = random.uniform(1.0, 2.5)
            print(f'遅延処理 {delay} 秒')
            time.sleep(delay)

            total_impressions = 0
            total_clicks = 0
            total_ctr = 0
            total_position = 0

            count = 0  # データの数を数える

            for row in search_url_data:
                impressions = row.get('impressions', 0)
                clicks = row.get('clicks', 0)
                ctr = row.get('ctr', 0)
                position = row.get('position', 0)

                total_impressions += impressions
                total_clicks += clicks
                total_ctr += ctr
                total_position += position

                count += 1

            if count > 0:
                avg_ctr = total_ctr / count
                avg_position = total_position / count
            else:
                avg_ctr = 0
                avg_position = 0

            # CSVファイルに書き込む
            csv_writer.writerow([original_url, total_impressions, total_clicks, avg_ctr, avg_position])

            print(f'URL: {original_url}, 合計表示回数: {total_impressions}, 合計クリック数: {total_clicks}, 平均CTR: {avg_ctr}, 平均掲載順位: {avg_position}')

    print(f'CSVファイルを以下のディレクトリにエクスポートしました: {output_file}')

except Exception as err:
    print(f'An error occurred: {err}')
