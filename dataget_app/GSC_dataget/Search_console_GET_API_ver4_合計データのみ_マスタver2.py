import sys
import os
sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '..')))
from setting_file.header import *
# from setting_file.Search_Console_set.url_base_master_total import URLS as Individual_urls
# from setting_file.Search_Console_set.qshaoh_index_url import URLS as Individual_urls
from setting_file.Search_Console_set.qshaoh_noindex_url import URLS as Individual_urls

from datetime import datetime, timedelta
import calendar

file_directory = file_path.file_directory # file_path.py で定義したファイルディレクトリを指定
file_name = "Search_Console_API_URL.csv"
output_file = os.path.join(file_directory, file_name)

header_row = ['URL', '取得日','合計表示回数', '合計クリック数', '平均CTR', '平均掲載順位']
delay_set = random.uniform(1.0, 2.5)

# 対象のサイトURLを指定
site_url = 'https://www.qsha-oh.com/'

# 日付セット
start_date_set = '2025-01-01'
end_date_set = '2025-01-31'

# 週毎、月毎、指定した日付のみ、のデータ取得を指定
interval_getdata_index = 2
# 設定index
interval_getdata_set_Wek = 'weekly'
interval_getdata_set_Mon = 'monthly'
interval_getdata_Indexs = {
    1:interval_getdata_set_Wek,
    2:interval_getdata_set_Mon,
    }
interval_getdata_set = interval_getdata_Indexs[interval_getdata_index]

# 一致条件
search_ops_index = 1
# 設定index
operator_Equ = 'equals'  # 完全一致
operator_Con = 'contains'  # 部分一致
search_ops_Indexs = {
    1:operator_Equ,
    2:operator_Con
    }
search_ops_set = search_ops_Indexs[search_ops_index]


# JSONファイルのパスを指定
SERVICE_ACCOUNT_FILE = api_json.qsha_oh
# Search Console APIの認証情報を指定
credentials = service_account.Credentials.from_service_account_file(
    SERVICE_ACCOUNT_FILE, scopes=['https://www.googleapis.com/auth/webmasters.readonly']
)
# Search Console APIのバージョンとプロジェクトIDを指定します
api_version = 'v3'
service = build('webmasters', api_version, credentials=credentials)



# 日付関数
def generate_date_ranges(start_date, end_date, interval):
    """
    指定された間隔に基づいて日付範囲のリストを生成する。
    :param start_date: 開始日 (str, 'YYYY-MM-DD')
    :param end_date: 終了日 (str, 'YYYY-MM-DD')
    :param interval: 'weekly' or 'monthly'
    :return: List of (startDate, endDate)
    """
    date_ranges = []
    current_start = datetime.strptime(start_date, '%Y-%m-%d')
    end_date = datetime.strptime(end_date, '%Y-%m-%d')

    while current_start < end_date:
        if interval == 'weekly':
            # 7日後を終了日とする
            current_end = current_start + timedelta(days=7)
        elif interval == 'monthly':
            # 翌月1日を終了日とする
            if current_start.month == 12:  # 12月の場合
                current_end = datetime(current_start.year + 1, 1, 1)
            else:
                current_end = datetime(current_start.year, current_start.month + 1, 1)
        else:
            raise ValueError("Invalid interval: must be 'weekly' or 'monthly'.")

        # `current_end` が `end_date` を超えないように調整
        date_ranges.append((current_start.strftime('%Y-%m-%d'), min(current_end, end_date).strftime('%Y-%m-%d')))
        current_start = current_end

    return date_ranges

def get_search_url_data(site_url, page_url, start_date, end_date):
    request = {
        'startDate': start_date,
        'endDate': end_date,
        'dimensions': ['page'],
        'searchType': 'web',
        'dimensionFilterGroups': [{
            'filters': [{
                'dimension': 'page',
                'operator': search_ops_set,
                'expression': page_url
            }]
        }]
    }

    try:
        response = service.searchanalytics().query(siteUrl=site_url, body=request).execute()
        return response.get('rows', []), page_url
    except Exception as e:
        print(f'Error retrieving data for URL {page_url} ({start_date} to {end_date}): {e}')
        return [], page_url

def main(start_date, end_date, interval_getdata):
    try:
        with open(output_file, 'w', newline='', encoding='utf-8') as csvfile:
            csv_writer = csv.writer(csvfile)
            # ヘッダー行を書き込む
            csv_writer.writerow(header_row)

            # 日付範囲を生成
            date_ranges = generate_date_ranges(start_date, end_date, interval_getdata)

            for url in Individual_urls:
                for start_date, end_date in date_ranges:
                    # URLの統計情報を取得
                    search_url_data, original_url = get_search_url_data(site_url, url, start_date, end_date)

                    # ランダムな遅延処理を追加
                    delay = delay_set
                    print(f'遅延処理 {delay:.2f} 秒')
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
                    csv_writer.writerow([original_url, f'{start_date} - {end_date}', total_impressions, total_clicks, avg_ctr, avg_position])

                    print(f'URL: {original_url}, 日付: {start_date} - {end_date}, 合計表示回数: {total_impressions}, 合計クリック数: {total_clicks}, 平均CTR: {avg_ctr}, 平均掲載順位: {avg_position}')

        print(f'CSVファイルを以下のディレクトリにエクスポートしました: {output_file}')

    except Exception as err:
        print(f'An error occurred: {err}')

main(start_date=start_date_set, end_date=end_date_set, interval_getdata=interval_getdata_set)
