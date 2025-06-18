from datetime import date, timedelta

def get_month_end(d):
    next_month = d.replace(day=28) + timedelta(days=4)
    return next_month - timedelta(days=next_month.day)

def generate_monthly_date_ranges(start_year=2023, start_month=4):
    today = date.today()
    current = date(start_year, start_month, 1)
    ranges = []

    while True:
        start_date = current
        end_date = get_month_end(current)
        ranges.append((start_date, end_date))

        if start_date.year == today.year and start_date.month == today.month:
            break

        if current.month == 12:
            current = date(current.year + 1, 1, 1)
        else:
            current = date(current.year, current.month + 1, 1)

    return ranges
