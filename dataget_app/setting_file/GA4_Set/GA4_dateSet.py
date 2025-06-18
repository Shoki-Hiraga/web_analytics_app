from datetime import date, timedelta

start_year=2023
start_month=4

# 月末を求める関数
def get_month_end(d):
    next_month = d.replace(day=28) + timedelta(days=4)
    return next_month - timedelta(days=next_month.day)

# 2023年4月1日から実行日当月までの (start_date, end_date) ペアをリストで返す
def generate_monthly_date_ranges(year=start_year, month=start_month):
    today = date.today()
    current = date(year, month, 1)
    ranges = []

    while True:
        start_date = current
        end_date = get_month_end(current)
        ranges.append((start_date, end_date))

        if start_date.year == today.year and start_date.month == today.month:
            break

        # 翌月へ
        if current.month == 12:
            current = date(current.year + 1, 1, 1)
        else:
            current = date(current.year, current.month + 1, 1)

    return ranges
